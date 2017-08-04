# SYSTEM OF FACULTAD VETERINARIA SAN CARLOS GUATEMALA

[![Code FMVZ](https://codeclimate.com/github/Foxandxss/angular-toastr.png)](https://codeclimate.com/github/Foxandxss/angular-toastr) 
[![Build FMVZ](https://travis-ci.org/Foxandxss/angular-toastr.svg?branch=master)](https://travis-ci.org/Foxandxss/angular-toastr) [![devDependency Status](https://david-dm.org/Foxandxss/angular-toastr/dev-status.svg)](https://david-dm.org/Foxandxss/angular-toastr#info=devDependencies)

The goal is to provide the same software than the original.

## Demo

[Demo](http://www.fmvz.usac.edu.gt/)

## Installation

Use npm:

```
$ npm install angular-toastr
```

If you are not using npm (you should), you can use bower:

```
$ bower install angular-toastr
```

To use a CDN, you can include the next two lines:

```html
<script src="https://unpkg.com/angular-toastr/dist/angular-toastr.tpls.js"></script>
<link rel="stylesheet" href="https://unpkg.com/angular-toastr/dist/angular-toastr.css" />
```

Or you can grab the latest [release](https://github.com/Foxandxss/angular-toastr/releases) and add both the `css` and `javascript` file:

```html
<link rel="stylesheet" type="text/css" href="angular-toastr.css" />
<script type="text/javascript" src="angular-toastr.tpls.js"></script>
```

**Note:** If you add a script tag for angular-toastr, keep in mind that you need the `tpls` version **or** the other depending if you want the default template or not (see below).

If you want animations, don't forget to add `angular-animate`.

Then add `toastr` to your modules dependencies:

```javascript
angular.module('app', ['ngAnimate', 'toastr'])
```

## Usage

Toastr usage is very simple, by default it comes with four types of notification messages:

Success:

```javascript
app.controller('foo', function($scope, toastr) {
  toastr.success('Hello world!', 'Toastr fun!');
});
```

![Success Image](http://i.imgur.com/5LTPLFK.png)

Info:

```javascript
app.controller('foo', function($scope, toastr) {
  toastr.info('We are open today from 10 to 22', 'Information');
});
```

![Info Image](http://i.imgur.com/7coIu7q.png)

Error:

```javascript
app.controller('foo', function($scope, toastr) {
  toastr.error('Your credentials are gone', 'Error');
});
```

![Error Image](http://i.imgur.com/sXdKsDK.png)

Warning:

```javascript
app.controller('foo', function($scope, toastr) {
  toastr.warning('Your computer is about to explode!', 'Warning');
});
```

![Warning Image](http://i.imgur.com/k4g8vMz.png)

Apart from that you can customize your basic toasts:

No t## Credits

All the credits for the guys at Erick Suy and Developers FMVZ for creating the original implementation.

## License

Copyright License: 
