



###

	new Xhr({ 
    endpoint: '/html5/upload'
    params: {  }
    onReadyStateChange: (e) ->
  })

###


window.FiveKit = {} unless window.FiveKit

class window.FiveKit.Xhr
  constructor: (@options) ->
    if @options.params
      @query = $.param( @options.params )
    else if @options.form
      @query = $(@options.form).serialize()
    else if @options.query
      @query = @options.query
    self = this

    @xhr = new XMLHttpRequest
    @xhr.upload.addEventListener 'loadstart',  @options.onTransferStart      if @options.onTransferStart
    @xhr.upload.addEventListener 'loadend',    @options.onTransferEnd        if @options.onTransferEnd
    @xhr.upload.addEventListener 'progress',   @options.onTransferProgress   if @options.onTransferProgress

    @dfd = $.Deferred()

    if @options.onTransferComplete
      @bind 'load', (e) ->
        target = e.srcElement or e.target
        console.debug target.responseText if window.console
        result = JSON.parse(target.responseText)
        if window.console
          if result.error
            console.error('result',result)
          else
            console.debug('result',result)
        self.options.onTransferComplete.call(this,e,result)
        self.dfd.resolve()
    @bind('error', @options.onTransferFailed) if @options.onTransferFailed
    @bind('abort', @options.onTransferCanceled) if @options.onTransferCanceled
    @xhr.onreadystatechange = @options.onReadyStateChange if @options.onReadyStateChange
    @open(@options.endpoint,@query)

  open: (endpoint,query) -> @xhr.open('POST', endpoint + '?' + query , true)

  # progress, load, error, abort
  bind: (evtname,cb) -> @xhr.addEventListener( evtname , cb, false)

  statechange: (cb) -> @bind('statechange',cb)

  progress: (cb) -> @bind('progress',cb)

  send: (file) ->

    if typeof FormData != "undefined"
      console.log("Sending file",file) if window.console
      # Chrome 7 sends data but you must use the base64_decode on the PHP side
      # @xhr.setRequestHeader("Content-Type", "multipart/form-data")
      @xhr.setRequestHeader("X-UPLOAD-FILENAME", encodeURIComponent(file.name))
      # @xhr.setRequestHeader("X-UPLOAD-FILENAME", file.name)
      @xhr.setRequestHeader("X-UPLOAD-SIZE", file.size)
      @xhr.setRequestHeader("X-UPLOAD-TYPE", file.type)
      @xhr.setRequestHeader("X-UPLOAD-MODIFIED-DATE", encodeURIComponent(file.lastModifiedDate.toISOString()))

      fd = new FormData
      fd.append "upload",file
      @xhr.send fd
    else if @xhr.sendAsBinary
      # Firefox 3.6 provides a feature sendAsBinary ()
      # sendAsBinary() is NOT a standard and may not be supported in Chrome.
      # XXX: currently broken because the API changed.
      console.log('sendAsBinary',file) if window.console
      mimeBuilder = new FiveKit.MimeBuilder
      mimeBuilder.build({
        file: file
        onBuilt: (b) =>
          console.log "body", b
          # use XHR HTTP Request to send file
          # @xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' + b.boundary)
          #
          @xhr.sendAsBinary(b.body)
      })

    return @dfd
    # bin is from reader.result (binary)
    # @xhr.send(window.btoa(bin))
