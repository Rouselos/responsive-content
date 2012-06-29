## Responsive Content

## Basics

Responsive Content is a jQuery plugin to help you serve differing content based on device screen-size and capability.

Page content is an HTML fragment pulled in via ajax, and inserted into a specified container element. 
Each ajax request carries device-dependent query parameters which you can use (server side) to tailor 
the content returned in the fragment.

The device-dependent query parameters are:
```
_rescon        // Indicates that a HTML fragment should be served, i.e. omitting surrounding HTML furniture. 
_rescon_width  // The lower bound of the current screen-width range from a set of ranges. Integer. 
_rescon_touch  // The device has touch/swipe capability. true|false
_rescon_retina // The device has a retina screen. true|false
```

## Usage

Call it on a jQuery selector wrapper that returns a single content-container element: 

```javascript
$('#contentContainer').responsiveContent({
	afterLoad: function(){}, // Optional. A callback function to run after each ajax load
  forceLoad: false,        // Optional. Force an initial ajax load, perhaps if you don't have default content. Default is false
  linkSelector: 'a',       // Optional. A jQuery selector for links that should cause ajax loading. Default is 'a'.
  widths: [ 0, 640 ]       // Optional. Width breakpoints. Default is [ 0, 481, 768, 1024 ].
})
```
## Default Content

Include default content in your content-container element. If the screen width exceeds the 
first (non zero) breakpoint in your set of defined breakpoints, the ajax call will be made 
and the default content will be replaced by the ajax response. 

## Screen Resizing

Ajax calls will be made if you resize the screen past the defined breakpoints. Useful in 
development, to see live the effect of screen size on content. 

## Example Sites

A site with (subtly) responsive content: http://thechapmagazine.co.uk/

## Credits

Responsive content is by Stephan Fowler, and is a fork of 
Chris Wanstrath's wonderful [Pjax](https://github.com/defunkt/jquery-pjax).