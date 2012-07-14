# Responsive Content

A jQuery plugin that helps you serve different content to different devices. 

Responsive Content doesn't use User Agent detection, relying only on device screen width 
and secondary device capabilities. HTML page content is loaded as a single fragment by Ajax
and inserted into a container element. Each Ajax fragment request carries device-dependent 
query values which can be referred to on the server side to tailor the returned HTML fragment 
to the current device.

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
The available options are _all optional_:

* **triggerWidth**: If the screen width is greater than or equal to this value, an Ajax reload is triggered. Default is 768.

* **afterLoad**: A callback function to run secondary logic after each load. This function is called once
per logical "page", i.e. for each of these scenarios: 
(1) the initial page requires no Ajax reload because the screen width is smaller than triggerWidth; 
(2) the initial page makes an Ajax reload because screen width is at least triggerWidth;
(3) a subsequent click causes a Pjax load; 
(4) a page is resized (and you've set `emulator: true`). Default is an empty function.

* **emulator**: Reload the fragment when the browser window is resized. Switches the metric to window width, rather than screen width. Default is `false`.

* **linkSelector**: The jQuery selector for elements to Pjax-ify. Must only select anchor tags. The default is `'a'`.

* **capabilities**: An object containing whatever other capabilities that you might need to pass to the server (screen width is always passed). The default is `{}`.

For example: 

```javascript
$('#myContainer').responsiveContent({

	// Tell the server about the device's touch capability and whether it's a retina screen
	capabilities: {
		touch: 'ontouchstart' in document.documentElement,
		pixelratio: window.devicePixelRatio && window.devicePixelRatio > 1 ? window.devicePixelRatio : 1
	},

	// Switch on the emulator if the url has is #emulator
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

The query parameters passed by each Ajax request are:

* **\_rescon**: Indicates that an HTML fragment should be served.

* **\_rescon\_reload**: Equals 1 when the request is a 'same page' reload, i.e. screen width exceeds `triggerWidth` 
or (only when `emulator: true`) a browser window resize has caused a reload 

* **\_rescon\_width**: The screen width, or (only when `emulator: true`) the window width. 

* **\_rescon\_&lt;capability&gt;**: The value of the capability. In the above case you could receive `_rescon_touch:true` and `_rescon_pixelratio:2` from an iPhone, for example. 

Use these as appropriate to alter the HTML fragment that you return. How you do this is entirely up to you. 

If the `_rescon` query parameter is present in a request, render a fragment that
omits all surrounding HTML - and especially the &lt;script&gt; tag that contains or loads the '$('#myContainer').responsiveContent()' function call. 
This is important in order to prevent perpetual request loops.

Make sure that the above `_rescon*` query parameters do not leak through and reappear in anchor 
href attributes in the returned HTML fragment. 

### "Mobile First" Content Flow

If the screen width equals or exceeds the `triggerWidth` value, an Ajax call will be made 
and its response will replace the "default content" in the container element. This default content should be the "mobile first" version of your content.
If you do not have default content and wish to force an Ajax load in all cases, set `triggerWidth: 0`.

Subsequent clicks on links will cause new "pages" (i.e. fragments, device tailored) to be loaded into the 
container element using [Pjax](https://github.com/defunkt/jquery-pjax). This causes the address bar and
history state to be updated with the link's href URL, ensuring correct back/forward button behaviour. Pjax also 
caches DOM fragments so that post-Ajax page state is maintained when navigating the history. 

NOTE: the latter paragraph only applies to browsers that support `history.pushState`. IE9 does not, for example. 
In this case the fallback behaviour is to always load the entire page as normal including its default content, 
followed by device-tailored content via Ajax if the screen width exceeds the `triggerWidth` value.

### Cache Considerations

The approach is cache-friendly. URLs for pages and HTML fragments (with their query parts) are deterministic 
and independent of User Agent or cookies, and are thus effective as regular cache keys. 

### Device width Emulator

If you set `emulator: true` and resize the browser window, Ajax calls will be made and the content will 
be reloaded, according to the current window width, rather than the fixed screen width. This is useful in development for check 
how the resulting content, but should be used with caution on production sites (for instance if you are pinging analytics services within the `afterLoad` callback.)
The reloads are throttled to one per second as you drag the browser windon edge.

### Example Sites

[The Chap Magazine](http://thechapmagazine.co.uk/). The 
number of articles on the front page decreases on lower screen sizes, as does the actual size of 
images in articles. Try it in <a href="http://thechapmagazine.co.uk/#emulator">emulator mode</a>.

[Metro Blogs](http://blogs.metro.co.uk/). Sidebars are not delivered to small screens. Images in the front page top section
and in articles decrease in actual size for smaller screens. Section pages have fewer article links on smaller screens.

(These sites also use Responsive Design.)

***
Responsive Content is a fork of [Pjax](https://github.com/defunkt/jquery-pjax).
