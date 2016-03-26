



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

  send: (file) ->
    self = this

    @xhr = new XMLHttpRequest
    @xhr.upload.addEventListener 'loadstart',  @options.onTransferStart      if @options.onTransferStart
    @xhr.upload.addEventListener 'loadend',    @options.onTransferEnd        if @options.onTransferEnd
    @xhr.upload.addEventListener 'progress',   @options.onTransferProgress   if @options.onTransferProgress
    dfd = $.Deferred()
    if @options.onTransferComplete
      @xhr.addEventListener 'load', (e) ->
        target = e.srcElement or e.target
        console.debug target.responseText if window.console
        result = JSON.parse(target.responseText)
        if result.error
          console.error('Action result',result) if window.console
        else
          console.debug('Action result',result) if window.console
        self.options.onTransferComplete.call(this,e,result)
        dfd.resolve(e, result)
    @xhr.addEventListener('error', @options.onTransferFailed, false) if @options.onTransferFailed
    @xhr.addEventListener('abort', @options.onTransferCanceled, false) if @options.onTransferCanceled
    @xhr.onreadystatechange = @options.onReadyStateChange if @options.onReadyStateChange
    @xhr.open('POST', @options.endpoint + '?' + @query, true)

    # See if FormData is supported.
    if typeof FormData isnt "undefined"
      console.info("Sending file using FormData...",file) if window.console
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
      console.info('Sending file using sendAsBinary', file) if window.console
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
    return dfd
    # bin is from reader.result (binary)
    # @xhr.send(window.btoa(bin))
