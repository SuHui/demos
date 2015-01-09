## pjax项目地址

https://github.com/defunkt/jquery-pjax/

## 优点

不刷新加载新内容，同时地址栏发生变化，支持前进后退操作
pjax = pushState + ajax

## 用法

``` html
<!DOCTYPE html>
<html>
<head>
  <!-- styles, scripts, etc -->
</head>
<body>
  <h1>My Site</h1>
  <div class="container" id="pjax-container">
    Go to <a href="/page/2">next page</a>.
  </div>
</body>
</html>
```

``` javascript
$(document).pjax('a', '#pjax-container')
```
从/page/2取得数据，填充在#pajx-container

pjax发送的网络请求中会带有 `X-PJAX` header.

## 兼容性

pjax only works with [browsers that support the `history.pushState` API][compat]. When the API isn't supported pjax goes into fallback mode: `$.fn.pjax` calls will be a no-op and `$.pjax` will hard load the given url. This mode targets the browser requirements of the jQuery version being used.

For debugging purposes, you can intentionally disable pjax even if the browser supports `pushState`. Just call `$.pjax.disable()`. To see if pjax is actually supports `pushState`, check `$.support.pjax`.

#### 参数

The synopsis for the `$.fn.pjax` function is:

``` javascript
$(document).pjax(selector, [container], options)
```

1. `selector` is a string to be used for click [event delegation][$.fn.on].
2. `container` is a string selector that uniquely identifies the pjax container.
3. `options` is an object with keys described below.

##### pjax options

key | default | description
----|---------|------------
`timeout` | 650 | ajax timeout in milliseconds after which a full refresh is forced
`push` | true | use [pushState][] to add a browser history entry upon navigation
`replace` | false | replace URL without adding browser history entry
`maxCacheLength` | 20 | maximum cache size for previous container contents
`version` | | a string or function returning the current pjax version
`scrollTo` | 0 | vertical position to scroll to after navigation
`type` | `"GET"` | see [$.ajax][]
`dataType` | `"html"` | see [$.ajax][]
`container` | | CSS selector for the element where content should be replaced
`url` | link.href | a string or function that returns the URL for the ajax request
`target` | link | eventually the `relatedTarget` value for [pjax events](#events)
`fragment` | `"body"` | CSS selector for the fragment to extract from ajax response

You can change the defaults globally by writing to the `$.pjax.defaults` object:

``` javascript
$.pjax.defaults.timeout = 1200
```

### `$.pjax.click`

This is a lower level function used by `$.fn.pjax` itself. It allows you to get a little more control over the pjax event handling.

This example uses the current click context to set an ancestor as the container:

``` javascript
if ($.support.pjax) {
  $(document).on('click', 'a[data-pjax]', function(event) {
    var container = $(this).closest('[data-pjax-container]')
    $.pjax.click(event, {container: container})
  })
}
```

**NOTE** Use the explicit `$.support.pjax` guard. We aren't using `$.fn.pjax` so we should avoid binding this event handler unless the browser is actually going to use pjax.

### `$.pjax.submit`

Submits a form via pjax. This function is experimental but GitHub uses it on [Gist][gist] so give it a shot!

``` javascript
$(document).on('submit', 'form[data-pjax]', function(event) {
  $.pjax.submit(event, '#pjax-container')
})
```

### `$.pjax.reload`

Initiates a request for the current URL to the server using pjax mechanism and replaces the container with the response. Does not add a browser history entry.

``` javascript
$.pjax.reload('#pjax-container', options)
```

### `$.pjax`

Manual pjax invocation. Used mainly when you want to start a pjax request in a handler that didn't originate from a click. If you can get access to a click `event`, consider `$.pjax.click(event)` instead.

``` javascript
function applyFilters() {
  var url = urlForFilters()
  $.pjax({url: url, container: '#pjax-container'})
}
```

### Events

All pjax events except `pjax:click` & `pjax:clicked` are fired from the pjax
container, not the link that was clicked.

<table>
<tr>
  <th>event</th>
  <th>cancel</th>
  <th>arguments</th>
  <th>notes</th>
</tr>
<tr>
  <th colspan=4>event lifecycle upon following a pjaxed link</th>
</tr>
<tr>
  <td><code>pjax:click</code></td>
  <td>✔︎</td>
  <td><code>options</code></td>
  <td>fires from a link that got activated; cancel to prevent pjax</td>
</tr>
<tr>
  <td><code>pjax:beforeSend</code></td>
  <td>✔︎</td>
  <td><code>xhr, options</code></td>
  <td>can set XHR headers</td>
</tr>
<tr>
  <td><code>pjax:start</code></td>
  <td></td>
  <td><code>xhr, options</code></td>
  <td></td>
</tr>
<tr>
  <td><code>pjax:send</code></td>
  <td></td>
  <td><code>xhr, options</code></td>
  <td></td>
</tr>
<tr>
  <td><code>pjax:clicked</code></td>
  <td></td>
  <td><code>options</code></td>
  <td>fires after pjax has started from a link that got clicked</td>
</tr>
<tr>
  <td><code>pjax:beforeReplace</code></td>
  <td></td>
  <td><code>contents, options</code></td>
  <td>before replacing HTML with content loaded from the server</td>
</tr>
<tr>
  <td><code>pjax:success</code></td>
  <td></td>
  <td><code>data, status, xhr, options</code></td>
  <td>after replacing HTML content loaded from the server</td>
</tr>
<tr>
  <td><code>pjax:timeout</code></td>
  <td>✔︎</td>
  <td><code>xhr, options</code></td>
  <td>fires after <code>options.timeout</code>; will hard refresh unless canceled</td>
</tr>
<tr>
  <td><code>pjax:error</code></td>
  <td>✔︎</td>
  <td><code>xhr, textStatus, error, options</code></td>
  <td>on ajax error; will hard refresh unless canceled</td>
</tr>
<tr>
  <td><code>pjax:complete</code></td>
  <td></td>
  <td><code>xhr, textStatus, options</code></td>
  <td>always fires after ajax, regardless of result</td>
</tr>
<tr>
  <td><code>pjax:end</code></td>
  <td></td>
  <td><code>xhr, options</code></td>
  <td></td>
</tr>
<tr>
  <th colspan=4>event lifecycle on browser Back/Forward navigation</th>
</tr>
<tr>
  <td><code>pjax:popstate</code></td>
  <td></td>
  <td></td>
  <td>event <code>direction</code> property: &quot;back&quot;/&quot;forward&quot;</td>
</tr>
<tr>
  <td><code>pjax:start</code></td>
  <td></td>
  <td><code>null, options</code></td>
  <td>before replacing content</td>
</tr>
<tr>
  <td><code>pjax:beforeReplace</code></td>
  <td></td>
  <td><code>contents, options</code></td>
  <td>right before replacing HTML with content from cache</td>
</tr>
<tr>
  <td><code>pjax:end</code></td>
  <td></td>
  <td><code>null, options</code></td>
  <td>after replacing content</td>
</tr>
</table>

`pjax:send` & `pjax:complete` are a good pair of events to use if you are implementing a
loading indicator. They'll only be triggered if an actual XHR request is made,
not if the content is loaded from cache:

``` javascript
$(document).on('pjax:send', function() {
  $('#loading').show()
})
$(document).on('pjax:complete', function() {
  $('#loading').hide()
})
```

An example of canceling a `pjax:timeout` event would be to disable the fallback
timeout behavior if a spinner is being shown:

``` javascript
$(document).on('pjax:timeout', function(event) {
  // Prevent default timeout redirection behavior
  event.preventDefault()
})
```

### Server side

Server configuration will vary between languages and frameworks. The following example shows how you might configure Rails.

``` ruby
def index
  if request.headers['X-PJAX']
    render :layout => false
  end
end
```

An `X-PJAX` request header is set to differentiate a pjax request from normal XHR requests. In this case, if the request is pjax, we skip the layout html and just render the inner contents of the container.

Check if your favorite server framework supports pjax here: https://gist.github.com/4283721

#### Layout Reloading

Layouts can be forced to do a hard reload when assets or html changes.

First set the initial layout version in your header with a custom meta tag.

``` html
<meta http-equiv="x-pjax-version" content="v123">
```

Then from the server side, set the `X-PJAX-Version` header to the same.

``` ruby
if request.headers['X-PJAX']
  response.headers['X-PJAX-Version'] = "v123"
end
```

Deploying a deploy, bumping the version constant to force clients to do a full reload the next request getting the new layout and assets.

### Legacy API

Pre 1.0 versions used an older style syntax that was analogous to the now deprecated `$.fn.live` api. The current api is based off `$.fn.on`.

``` javascript
$('a[data-pjax]').pjax('#pjax-container')
```

Expanded to

``` javascript
$('a[data-pjax]').live('click', function(event) {
  $.pjax.click(event, '#pjax-container')
})
```

The new api

``` javascript
$(document).pjax('a[data-pjax]', '#pjax-container')
```

Which is roughly the same as

``` javascript
$(document).on('click', 'a[data-pjax]', function(event) {
  $.pjax.click(event, '#pjax-container')
})
```

**NOTE** The new api gives you control over the delegated element container. `$.fn.live` always bound to `document`. This is what you still want to do most of the time.

