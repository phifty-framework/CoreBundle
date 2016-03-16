window.FiveKit = {} if typeof window.FiveKit is "undefined"

###
# FileUploader uploads multiple files 
###
class FiveKit.BatchFileUploader extends FiveKit.FileUploader

  uploadFile: (csrfToken, file) ->
    self = this
    if @progressContainer
      progressItem = new FiveKit.UploadProgressItem(file)
      progressItem.el.appendTo(@progressContainer)

    xhr = new FiveKit.Xhr
      endpoint: @config.endpoint
      params: {
        __action: @actionClass
        __ajax_request: 1
        __csrf_token: csrfToken.hash
      }
      onReadyStateChange: (e) ->
        console.debug('onReadyStateChange',e) if window.console
        self.config.onReadyStateChange.call(this,e) if self.config.onReadyStateChange

      onTransferStart : (e) ->
        console.debug('onTransferStart', e) if window.console
        self.config.onTransferStart.call(this,e) if self.config.onTransferStart

      onTransferProgress: (e) ->
        console.debug('onTransferProgress',e) if window.console
        self.config.onTransferProgress.call(this,e) if self.config.onTransferProgress

        if e.lengthComputable
          position = (e.position or e.loaded)
          total = (e.totalSize or e.total)
          console.log('progressing',e, position , total ) if window.console
          progressItem.update(position, total) if progressItem
      onTransferComplete: (e, result) ->
        self.config.onTransferComplete.call(this, e, result, progressItem)
    return xhr.send(file)

  upload: (files) ->
    ActionCsrfToken.get success: (csrfToken) =>
      rs = []
      for file in files
        do (file) =>
          rs.push @uploadFile(csrfToken, file)
      $.when.apply($,rs).done(@config.onTransferFinished ) if @config.onTransferFinished
