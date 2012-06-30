# Responsive Content

Responsive Content is a jQuery plugin to help you serve differing content based on device screen-size and capability. 
The page content is an HTML fragment pulled in via Ajax, and inserted into a specified container element. 
Each ajax request carries device-dependent query parameters which you can use (server side) to tailor 
the content returned in the fragment.

### Client Side

Call it on a jQuery selector wrapper that returns a single content-container element: 

```javascript
$('#contentContainer').responsiveContent({
  afterLoad: function(){}, // Optional. A callback function to run after each ajax load
  forceLoad: false,        // Optional. Force an initial ajax load, perhaps if you don't have default content. Default is false
  linkSelector: 'a',       // Optional. A jQuery selector for links that should cause ajax loading. Default is 'a'.
  widths: [ 0, 640 ]       // Optional. Width breakpoints. Default is [ 0, 481, 768, 1024 ].
})
```

### Server Side

The device-dependent query parameters in the Ajax request are:
```
_rescon        // Indicates that a HTML fragment should be served.
_rescon_width  // The lower bound of the current screen-width range from a set of ranges. Integer. 
_rescon_touch  // The device has touch/swipe capability. true|false
_rescon_retina // The device has a retina screen. true|false
```
Use these as appropriate to alter the HTML fragment that you return. How you do this is entirely up to you. 
You probably want to first check that `_rescon` is present, an if so render only a fragment, 
omitting all surrounding HTML furniture. 

### Content

You'll probably want to include default content in your content-container element. This basically means the 
"smallest" version of you content, i.e. suitable for smartphones. If the window width exceeds the 
first (non zero) breakpoint in your set of defined breakpoints, an Ajax call will be made 
and the default content will be replaced by the Ajax response. Alternatively, if you don't 
include such default content, you can use the `forceLoad: true` option to force an initial Ajax load.

Subsequent clicks on links will cause new content (device tailored) to be loaded into the page using
[Pjax](https://github.com/defunkt/jquery-pjax). 

NOTE: the latter step is only true for browsers that support `history.pushState`. IE9 does not, so will
load default content first following each click - followed by device-specific content if the width exceeds the 
first (non zero) breakpoint.

### Window Resizing

Ajax calls will be made if you resize the window past the defined breakpoints. This is mostly useful in 
development, to emulate the effect of various screen sizes on the content that is loaded. 

### Example Sites

[The Chap Magazine](http://thechapmagazine.co.uk/) has subtly responsive content using this technique. The 
number of articles on the front page decreases as you reduce window size, as does the the size of 
images in articles.

***
Responsive content is by [Stephan Fowler](http://uk.linkedin.com/in/stephanfowler), and is a fork of 
the wonderful [Pjax](https://github.com/defunkt/jquery-pjax).