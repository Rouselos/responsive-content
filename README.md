# Responsive Content

Responsive Content is a jQuery plugin to help you serve differing content to different devices. 
It can be used in parallel with CSS "responsive design" but is fundamentally different in that is loads different 
_content_ for different screen szes, rather than merely hiding/showing elements of the same content. It does not use 
User Agent detection, relying simply on window/screen width and secondary device capabilities.

Page content is loaded as HTML fragments by Ajax and inserted inta  besingle dspecified container element. 
Each ajax request has device-dependent query parameters which can be used (server side) to tailor 
the HTML in the returned fragment.

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
You probably want to first check that `_rescon` is present, and if so render only a fragment, 
omitting all surrounding HTML and especially the above jQuery function call. This is imortanrt 
in order to prevent perpetual request loops.

### Content Flow

If the window width exceeds the 
first (non zero) breakpoint in your set of defined breakpoints, an Ajax call will be made 
and its response will replace the "default content" in the container element. 

Default content is the "smallest" version of yout content, i.e. as suitable for smartphones. Alternatively, 
if you don't include such default content, you can use the `forceLoad: true` option to force an initial Ajax load.

Subsequent clicks on links will cause new "pages" (i.e. fragments, device tailored) to be loaded into the 
container element using [Pjax](https://github.com/defunkt/jquery-pjax). 

NOTE: the latter is only true for browsers that support `history.pushState`. IE9 does not, so will
load default content first following each click - followed by device-specific content if the window width exceeds the 
first (non zero) breakpoint.

### Cache Considerations

The approach is cache-friendly. URLS for pages and HTML fragments (with query parts) are deterministic 
and not dependent on User Agent or cookies, and thus effective as regular cache keys. 

### Window Resizing

If you resize the window width across the defined breakpoints, Ajax calls will be made and th econtent will 
be reloaded. This is mostly useful in development, to quickly check how device width variously affects the content 
that is loaded. 

### Example Sites

[The Chap Magazine](http://thechapmagazine.co.uk/) has subtly responsive content using this technique. The 
number of articles on the front page decreases as you reduce window size, as does the the size of 
images in articles.

***
Responsive content is by [Stephan Fowler](http://uk.linkedin.com/in/stephanfowler), and is a fork of 
the wonderful [Pjax](https://github.com/defunkt/jquery-pjax).