<img alt="Flyimglogo" src="https://raw.githubusercontent.com/flyimg/graphic-assets/master/logo/raster/flyimg-logo-rgb.png" width="300">

# Flyimg

[![Backers on Open Collective](https://opencollective.com/flyimg/backers/badge.svg)](#backers) [![Sponsors on Open Collective](https://opencollective.com/flyimg/sponsors/badge.svg)](#sponsors)
[![Build Status](https://travis-ci.org/flyimg/flyimg.svg?branch=master)](https://travis-ci.org/flyimg/flyimg)
[![Code Climate](https://codeclimate.com/github/flyimg/flyimg/badges/gpa.svg)](https://codeclimate.com/github/flyimg/flyimg)
[![Issue Count](https://codeclimate.com/github/flyimg/flyimg/badges/issue_count.svg)](https://codeclimate.com/github/flyimg/flyimg)
[![Test Coverage](https://codeclimate.com/github/flyimg/flyimg/badges/coverage.svg)](https://codeclimate.com/github/flyimg/flyimg/coverage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/89b18390-ac79-4c3e-bf6c-92cd9993e8d3/mini.png)](https://insight.sensiolabs.com/projects/89b18390-ac79-4c3e-bf6c-92cd9993e8d3)

Image resizing, cropping and compression on the fly with the impressive [MozJPEG](http://calendar.perfplanet.com/2014/mozjpeg-3-0) compression algorithm. One Docker container to build your own Cloudinary-like service.

### Fetch  an image from anywhere; resize, compress, cache and serve...<small> and serve, and serve, and serve...</small>

You pass the image URL and a set of keys with options, like size or compression. Flyimg will fetch the image, convert it, store it, cache it and serve it. The next time the request comes, it will serve the cached version.

```
<!-- https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg -->
<img src="https://my.img.service.io/upload/w_333,h_333,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg">
```
## For example
http://oi.flyimg.io/upload/w_300,h_250,c_1/https://m0.cl/t/resize-test_1920.jpg

![lago_ranco](http://oi.flyimg.io/upload/w_300,h_250,c_1/https://m0.cl/t/resize-test_1920.jpg)

Table of Contents
=================

   * [Requirements](#requirements)
   * [Installation [Deployment mode]](#installation-deployment-mode)
   * [Installation [Development Mode]](#installation-development-mode)
      * [Installation](#installation)
         * [with git](#with-git)
         * [with composer](#with-composer)
   * [Testing Flyimg service](#testing-flyimg-service)
   * [How to transform images](#how-to-transform-images)
      * [Options keys](#options-keys)
      * [Default options values](#default-options-values)
   * [Option details](#option-details)
      * [output string](#output-string)
      * [mozjpeg bool](#mozjpeg-bool)
      * [quality int (0-100)](#quality-int-0-100)
      * [width int](#width-int)
      * [height int](#height-int)
      * [Using width AND height](#using-width-and-height)
      * [crop bool](#crop-bool)
      * [gravity string](#gravity-string)
      * [background color (multiple formats)](#background-color-multiple-formats)
      * [strip int](#strip-int)
      * [resize int](#resize-int)
      * [unsharp radiusxsigma{ gain}{ threshold}](#unsharp-radiusxsigmagainthreshold)
      * [filter string](#filter-string)
      * [scale int](#scale-int)
      * [rotate string](#rotate-string)
      * [refresh int](#refresh-int)
      * [Face Crop int](#face-crop-int)
      * [Face Crop Position int](#face-crop-position-int)
      * [Face Blur int](#face-blur-int)
      * [Enable Restricted Domains](#enable-restricted-domains)
      * [Run test](#run-test)
      * [How to Provision the application on](#how-to-provision-the-application-on)
   * [Technology stack](#technology-stack)
      * [Abstract storage with Flysystem](#abstract-storage-with-flysystem)
         * [Using AWS S3 as Storage Provider](#using-aws-s3-as-storage-provider)
   * [Benchmark](#benchmark)
   * [Demo Application running](#demo-application-running)
   * [Roadmap](#roadmap)
   * [Contributors](#contributors)
   * [Backers](#backers)
   * [Sponsors](#sponsors)
   
   
# Requirements

You will need to have **Docker** on your machine. Optionally you can use Docker machine to create a virtual environment. We have tested on **Mac**, **Windows** and **Ubuntu**.

# Installation [Deployment mode]

Pull the image

```bash
docker pull flyimg/flyimg-build
```

Start the container

```bash
docker run -itd -p 8080:80 flyimg/flyimg-build
```
Check [how to provision the application](#how-to-provision-the-application-on)

# Installation [Development Mode]

You can spin up your own working server in 10 minutes using the provision scripts for [AWS Elastic Beanstalk](https://github.com/flyimg/Elastic-Beanstalk-provision) or the [DigitalOcean Ubuntu Droplets](https://github.com/flyimg/DigitalOcean-provision) <small>(more environments to come)</small>. For other environments or if you want to tweak and play in your machine before rolling out, read along...

## Installation

You can use `git` or `composer` for the first step. 

### with git

```sh
git clone https://github.com/flyimg/flyimg.git
```
### with composer
Create the project with `composer create` .

```sh
composer create-project flyimg/flyimg
```

**CD into the folder** and to build the docker image by running:

```sh
docker build -t flyimg .
```
This will download and build the main image, It will take a few minutes. If you get some sort of error related to files not found by apt-get or similar, try this same command again.

Then run the container:

```sh
docker run -itd -p 8080:80 -v $(pwd):/var/www/html --name flyimg flyimg
```

For Fish shell users: 

```sh
docker run -itd -p 8080:80 -v $PWD:/var/www/html --name flyimg flyimg
```

The above command will make the Dockerfile run supervisord command which launches 2 processes: **nginx** and **php-fpm** and starts listening on port 8080.

**IMPORTANT!** If you cloned the project, only for the first time, you need to run `composer install` **inside** the container:

```sh
docker exec -it flyimg composer install
```

Again, it will take a few minutes to download the dependencies. Same as before, if you get some errors you should try running `composer install` again.
 

# Testing Flyimg service

You can navigate to your machine's IP in port 8080 (ex: http://127.0.0.1:8080/ ) ; you should get a message saying: **Hello from Flyimg!** and a small homepage of flyimg already working. If you get any errors  at this stage it's most likely that composer has not finished installing or skipped something.

You can test your image resizing service by navigating to: http://127.0.0.1:8080/upload/w_130,h_113,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg

![ff-logo](http://oi.flyimg.io/upload/w_130,h_113,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg)

**It's working!**

This is fetching an image from Mozilla, resizing it, saving it and serving it.


# How to transform images

You go to your server URL`http://imgs.kitty.com` and append `/upload/`;  after that you can pass these options below, followed by an underscore and a value `w_250,q_50` Options are separated by coma (configurable to other separator) . 
After the options put the source of your image, it can be relative to your server or absolute: `/https://my.storage.io/imgs/pretty-kitten.jpg`
So to get a pretty kitten at 250 pixels wide, with 50% compression, you would write.
`<img src="http://imgs.kitty.com/upload/w_250,q_50/https://my.storage.io/imgs/pretty-kitten.jpg">`

---

Options keys
-------------

```yml
options_keys:
  moz: mozjpeg
  q: quality
  o: output
  unsh: unsharp
  fc: face-crop
  fcp: face-crop-position
  fb: face-blur
  w: width
  h: height
  c: crop
  bg: background
  st: strip
  rz: resize
  g: gravity
  f: filter
  r: rotate
  sc: scale
  sf: sampling-factor
  rf: refresh
  ett: extent
  par: preserve-aspect-ratio
  pns: preserve-natural-size
  webpl: webp-lossless
```

Default options values
-----------------------

```yml
default_options:
  mozjpeg: 1
  quality: 90
  output: auto
  unsharp: null
  face-crop: 0
  face-crop-position: 0
  face-blur: 0
  width: null
  height: null
  crop: null
  background: null
  strip: 1
  resize: null
  gravity: Center
  filter: Lanczos
  rotate: null
  scale: null
  sampling-factor: 1x1
  refresh: false
  extent: null
  preserve-aspect-ratio: 1
  preserve-natural-size: 1
  webp-lossless: 0
```

# Option details
Most of these options are ImageMagick flags, many can get pretty advanced, use the [ImageMagick docs](http://www.imagemagick.org/script/command-line-options.php). 
We put a lot of defaults in place to prevent distortion, bad quality 

### output `string`
**default: auto** : Output format requested, for example you can force the output as jpeg file in case of source file is png.

**example:`o_auto`,`o_png`,`o_webp`,`o_jpeg`,`o_jpg`** 


### mozjpeg `bool`
**default: 1** : Use moz-jpeg compression library, if `false` it fallback to the default ImageMagick compression algorithm.

**example:`moz_0`** 


### quality `int` (0-100)
**default: 90** : Sets the compression level for the output image. Your best results will be between **70** and **95**.

**example:`q_100`,`q_75`,...** 

`q_30`  :  `http://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg` 

[![q_30](http://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


`q_100`  :  `http://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![q_100](http://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


### width `int`
**default: null** : Sets the target width of the image. If not set, width will be calculated in order to keep aspect ratio.

**example:`w_100`** 

`w_100` :   `http://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![w_100](http://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### height `int`
**default: null** : Sets the target height of the image. If not set, height will be calculated in order to keep aspect ratio.

**example:`h_100`** 

`h_100`  : `http://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`
 
[![h_100](http://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### Using width AND height

**example:`h_300,w_300`** 
By default setting width and height together, works like defining a rectangle that will define a **max-width** and **max-height** and the image will scale propotionally to fit that area without cropping.
<!-- in the future put example images here-->

By default; width, height, or both will **not scale up** an image that is smaller than the defined dimensions.
<!-- in the future put example images here-->

`h_300,w_300` : `http://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![h_300,w_300](http://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### crop `bool` 
**default: false** : When both width and height are set, this allows the image to be cropped so it fills the **width x height** area.

**example:`c_1`** 

`c_1,h_400,w_400` : `http://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![c_1,h_400,w_400](http://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


### gravity `string`
**default: Center** : When crop is applied, changing the gravity will define which part of the image is kept inside the crop area.
The basic options are: `NorthWest`, `North`, `NorthEast`, `West`, `Center`, `East`, `SouthWest`, `South`, `SouthEast`.

**example:`g_West`** 

```sh
   [...] -gravity NorthWest ...

```

### background `color` (multiple formats) 
**default: white** : Sets the background of the canvas for the cases where padding is added to the images. It supports hex, css color names, rgb. 
Only css color names are supported without quotation marks.
For the hex code, the hash `#` character should be replaced by `%23` 

**example:`bg_red`,`bg_%23ff4455`,`bg_rgb(255,120,100)`,...** 

```sh
  [...] -background red ...
  [...] -background "#ff4455" -> "%23ff4455"
  [...] -background "rgb(255,120,100)" ...
```

`http://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![bg_red](http://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### strip `int`
**default: 1** : removes exif data and additional color profile.

**example:`st_1`** 

### resize `int`
**default: null** : The alternative resizing method to -thumbnail.

**example:`rz_1`** 

### unsharp `radiusxsigma{+gain}{+threshold}` 
**default: null** : Sharpens an image with a convolved Gausian operator. A good example `0.25x0.25+8+0.065`.

**example:`unsh_0.25x0.25+8+0.065`** 

```sh
   [...] -unsharp 0.25x0.25+8+0.065 ...
```

### filter `string`
**default: Lanczos** : Resizing algorithm, Triangle is a smoother lighter option

**example:`f_Triangle`** 

```sh
   [...] -filter Triangle
```

### scale `int`
**default: null** : The "-scale" resize operator is a simplified, faster form of the resize command. Useful for fast exact scaling of pixels.

**example:`sc_1`** 


### rotate `string`
**default: null** : Apply image rotation (using shear operations) to the image. 

**example: `r_90`, `r_-180`,...**

`r_45` :  `http://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![r_45](http://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

 
### refresh `int`
**default: false** : Refresh will delete the local cached copy of the file requested and will generate the image again. 
Also it will send headers with the command done on the image + info returned by the command identity from IM.

**example:`rf_1`** 

 
### Face Crop `int`
**default: false** : Using [facedetect](https://github.com/wavexx/facedetect) repository to detect faces and passe the coordinates to ImageMagick to crop.

**example:`fc_1`** 

`fc_1` :  `http://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg`

[![fc_1](http://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg)](http://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg)

 
### Face Crop Position `int`
**default: false** : When using the Face crop option and when the image contain more than one face, you can specify which one you want get cropped

**example:`fcp_1`,`fcp_0`,...** 

`fcp_2` : `http://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg`

[![fcp_2](http://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg)](http://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg)

 
### Face Blur `int`
**default: false** : Apply blur effect on faces in a given image

**example:`fb_1`** 

`fb_1`  : `http://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg`

[![fb_1](http://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg)](http://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg)


Enable Restricted Domains
--------------------------

Restricted domains disabled by default. This means that you can fetch a resource from any URL. To enable the domain restriction, change in config/parameters.yml 

```yml
restricted_domains: true
```

After enabling, you need to put the white listed domains

```yml
whitelist_domains:
    - www.domain-1.org
    - www.domain-2.org
```

Run test:
-----
```sh
docker exec -it flyimg vendor/bin/phpunit tests/
```

How to Provision the application on
-----------------------------------
- [DigitalOcean](https://github.com/flyimg/DigitalOcean-provision)
- [AWS Elastic-Beanstalk](https://github.com/flyimg/Elastic-Beanstalk-provision)

# Technology stack

* Server: nginx
* Application:  [Silex](http://silex.sensiolabs.org/) , a PHP microframework.
* Image manipulation: ImageMagik
* JPEG encoder: MozJpeg
* Storage: [Flysystem](http://flysystem.thephpleague.com/)
* Containerisation:  Docker

## Abstract storage with Flysystem

Storage files based on [Flysystem](http://flysystem.thephpleague.com/) which is `a filesystem abstraction allows you to easily swap out a local filesystem for a remote one. Technical debt is reduced as is the chance of vendor lock-in.`

Default storage is Local, but you can use other Adapters like AWS S3, Azure, FTP, Dropbox, ... 

Currently, only the local and S3 are implemented as Storage Provider in Flyimg application, but you can add your specific one easily in `src/Core/Provider/StorageProvider.php` 

### Using AWS S3 as Storage Provider

in parameters.yml change the `storage_system` option from local to s3, and fill in the aws_s3 options :

```yml
storage_system: s3

aws_s3:
  access_id: "s3-access-id"
  secret_key: "s3-secret-id"
  region: "s3-region"
  bucket_name: "s3-bucket-name"
```

# Benchmark

See [benchmark.sh](https://github.com/flyimg/flyimg/blob/master/benchmark.sh) for more details
Requires: Vegeta [http://github.com/tsenart/vegeta](http://github.com/tsenart/vegeta)

```
Crop http://localhost:8080/upload/w_200,h_200,c_1,/Rovinj-Croatia.jpg
Requests      [total, rate]            500, 50.10
Duration      [total, attack, wait]    9.986739873s, 9.97999984s, 6.740033ms
Latencies     [mean, 50, 95, 99, max]  25.076911ms, 6.640774ms, 188.643721ms, 331.643374ms, 636.521662ms
Bytes In      [total, mean]            213904, 427.81
Bytes Out     [total, mean]            0, 0.00
Success       [ratio]                  100.00%
Status Codes  [code:count]             200:500

Resize http://localhost:8080/upload/w_200,h_200,rz_1/Rovinj-Croatia.jpg
Requests      [total, rate]            500, 50.10
Duration      [total, attack, wait]    9.987057007s, 9.979999967s, 7.05704ms
Latencies     [mean, 50, 95, 99, max]  12.830654ms, 7.643809ms, 40.270126ms, 108.39337ms, 195.252615ms
Bytes In      [total, mean]            77994, 155.99
Bytes Out     [total, mean]            0, 0.00
Success       [ratio]                  100.00%
Status Codes  [code:count]             200:500

Rotate http://localhost:8080/upload/r_-45,w_400,h_400/Rovinj-Croatia.jpg
Requests      [total, rate]            500, 50.10
Duration      [total, attack, wait]    9.986181761s, 9.979999903s, 6.181858ms
Latencies     [mean, 50, 95, 99, max]  984.630622ms, 585.392512ms, 2.660701812s, 2.763049369s, 6.202687668s
Bytes In      [total, mean]            1304682, 2609.36
Bytes Out     [total, mean]            0, 0.00
Success       [ratio]                  100.00%
Status Codes  [code:count]             200:500
```

# Demo Application running

[http://oi.flyimg.io](http://oi.flyimg.io)

[http://oi.flyimg.io/upload/w_300,h_250,c_1/https://m0.cl/t/resize-test_1920.jpg]


# Roadmap

- [x] Benchmark the application.
- [ ] Decouple the core logic from Silex in order to make it portable.
- [ ] Test it with couple of frameworks, Phalcon Php is a good candidate.
- [ ] Add overlays functionality (Text on top of the image)
- [ ] Storage auto-mapping


# Contributors

This project exists thanks to all the people who contribute.
<a href="graphs/contributors"><img src="https://opencollective.com/flyimg/contributors.svg?width=890" /></a>


# Backers

Thank you to all our backers! [[Become a backer](https://opencollective.com/flyimg#backer)]

<a href="https://opencollective.com/flyimg#backers" target="_blank"><img src="https://opencollective.com/flyimg/backers.svg?width=890"></a>


# Sponsors

Thank you to all our sponsors! (please ask your company to also support this open source project by [becoming a sponsor](https://opencollective.com/flyimg#sponsor))

<a href="https://opencollective.com/flyimg/sponsor/0/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/1/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/2/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/3/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/4/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/5/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/6/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/7/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/8/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/9/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/9/avatar.svg"></a>



Licence: MIT


Enjoy your Flyimaging!
