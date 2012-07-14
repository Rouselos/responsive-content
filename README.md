# Responsive Content

A jQuery plugin that helps you serve different content to different devices. 

Responsive Content doesn't use User Agent detection, relying only on device screen width 
and secondary device capabilities. HTML page content is loaded as a single fragment by Ajax
and inserted into a container element. Each Ajax fragment request carries device-dependent 
query values which can be referred to on the server side to tailor the returned HTML fragment 
to the current device. Subsequent clicks are loaded by [Pjax](https://github.com/defunkt/jquery-pjax).

Whilst it can be used in parallel with CSS Responsive Design, it does something else completely. 
For a given screen size Responsive Design applies different styling to _identical_ content, whereas
Reponsive Content will actually load _different_ content. It can be used subtly - for instance to load 
smaller images on smaller devices - or to deliver radically different markup to different screen widths.  


### Client Side

Call it on a jQuery selector wrapper that returns a single content-container element. 

```javascript
$('#myContainer').responsiveContent({
	// options
})
```
The available options are all optional:

* `breakpoint` : if the screen width is greater than or equal to this value, an Ajax reload is triggered. Default is 768.

* `afterLoad` : a callback function to run secondary logic after each load. This function is called exactly once for each of these exclusive cases: 
(1) the initial page requires no Ajax reload because the screen width is smaller than _breakpoint_; 
(2) the initial page makes an Ajax reload because screen width is at least _breakpoint_;
(3) a subsequent click causes a Pjax load; 
(4) a page is resized (and you've set _emulator: true_). Default is an empty function.

* `emulator` : reload the fragment when the browser window is resized. Switches the metric to window width, rather than screen width. Default is _false_.

* `linkSelector` : the jQuery selector for elements to Pjax-ify. Must only select anchor tags. The default is _'a'_.

* `capabilities` : an object containing whatever other capabilities that you might need to pass to the server (screen width is always passed). The default is _{}_.

For example: 

```javascript
$('#myContainer').responsiveContent({

	// Tell the server about the device's touch capability and whether it's a retina screen
	capabilities: {
		touch: 'ontouchstart' in document.documentElement,
		pixelratio: window.devicePixelRatio && window.devicePixelRatio > 1 ? window.devicePixelRatio : 1
	},

	// Switch on the emulator if the url hash is #emulator
	emulator: /^#emulator$/.test(window.location.hash),

	// ping Google Analytics after each "page"
	afterLoad: function(){ 
		if ( '_gaq' in window ) {
			_gaq.push(['_trackPageview'])
		}
	}

})
```
### Server Side

The query parameters passed by each Ajax GET request are:

* `_rescon` : indicates that an HTML fragment should be served.

* `_rescon_reload` : equals 1 when the request is a reload caused by screen width exceeding _breakpoint_ 
(or if _emulator: true_ and a browser window resize has caused a reload).

* `_rescon_width` : the screen width (or the window width when _emulator: true_). 

* `_rescon_{capability name}` : the value of the capability. (In the above example, you might receive *_rescon_touch:true* and *_rescon_pixelratio:2* from an iPhone.) 

Use these as appropriate to alter the HTML fragment that you return. How you do this is entirely up to you. 

If the *_rescon* query parameter is present in a request, render a fragment that
omits all surrounding HTML, especially the &lt;script&gt; tag that contains or externally loads the `$('#myContainer').responsiveContent()` function call. 
This is important in order to prevent perpetual request loops.

Ensure also that the above __rescon*_ query parameters do not leak through and reappear in anchor 
href attributes in the returned HTML fragment. 

### "Mobile First" Content Flow

If the screen width equals or exceeds the _breakpoint_ option value, an Ajax call will be made 
and its response will replace the "default content" in the container element. This default content should be the "mobile first" version of your content.
If you do not have default content and wish to force an Ajax load in all cases, set _breakpoint: 0_.

Subsequent clicks on links will cause new "pages" (i.e. fragments, device tailored) to be loaded into the 
container element using [Pjax](https://github.com/defunkt/jquery-pjax). This causes the address bar and
history state to be updated with the link's href URL, ensuring correct back/forward button behaviour. Pjax also 
caches DOM fragments so that post-Ajax page state is maintained when navigating the history. 

NOTE: the latter paragraph only applies to browsers that support _history.pushState_. IE9 does not, for example. 
In this case the fallback behaviour is to always load the entire page as normal including its default content, 
followed by device-tailored content via Ajax if the screen width exceeds the _breakpoint_ option value.

### Cache Considerations

The approach is cache-friendly. Firstly, URLs for full pages are the same across all devices since the initial page state is the same for all devices, 
being progressively enhanced only if the device's screen width merits it. Secondly, all Ajax requests use the GET method with URLs
that - along with their query parameters - are fully deterministic. The URLs in both cases are thus effective as regular cache keys since they are not reliant for their
uniqueness on other HTTP request headers such as User Agent or Cookie.

### Device Emulator

If you set _emulator: true_ and resize the browser window, Ajax calls will be made and the content will 
be reloaded according to the current _window_ width, rather than the device's screen width. This is useful in development for viewing 
the resulting content variants, but should be used with caution on production sites (for instance, if you're pinging Google Analytic within the _afterLoad_ callback.)
The reloads are throttled to maximum of one per second as you drag the browser window edge.

### Example Sites

[The Chap Magazine](http://thechapmagazine.co.uk/). The number of articles on the front page decreases on lower screen sizes, as does the actual size of 
images in articles. Try it in <a href="http://thechapmagazine.co.uk/#emulator">emulator mode</a>.

[Metro Blogs](http://blogs.metro.co.uk/). Sidebars are not delivered to small screens. Images in the front page top section
and in articles decrease in actual size for smaller screens. Section pages have fewer article links on smaller screens.

(These sites also use Responsive Design.)

***
Responsive Content is a fork of [Pjax](https://github.com/defunkt/jquery-pjax).
