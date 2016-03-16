
window.FiveKit = {} unless window.FiveKit

class window.FiveKit.UploadQueue

class window.FiveKit.FileUploader
  constructor: (@files,@options) ->
    # progress queue container
    self = this
    @queueEl  = @options.queueEl
    @action = @options.action or "CoreBundle::Action::Html5Upload"

    ActionCsrfToken.get success: (csrfToken) =>
      rs = []
      for file in @files
        do (file) =>
          console.log "Got dropped file ", file if window.console
          progressItem = new FiveKit.UploadProgressItem(file)
          progressItem.el.appendTo(self.queueEl)
          xhr = new FiveKit.Xhr({
            endpoint: '/bs'
            params: {
              __action: self.action
              __ajax_request: 1
              _csrf_token: csrfToken.hash
            }
            onReadyStateChange: (e) ->
              console.log('onReadyStateChange',e) if window.console
              self.options.onReadyStateChange.call(this,e) if self.options.onReadyStateChange

            onTransferStart : (e) ->
              console.log('onTransferStart', e) if window.console
              self.options.onTransferStart.call(this,e) if self.options.onTransferStart

            onTransferProgress: (e) ->
              console.log('onTransferProgress',e) if window.console
              self.options.onTransferProgress.call(this,e) if self.options.onTransferProgress

              if e.lengthComputable
                position = e.position or e.loaded
                total = e.totalSize or e.total
                console.log('progressing',e, position , total ) if window.console
                progressItem.update( position, total )

            onTransferComplete: (e, result) ->
              self.options.onTransferComplete.call(this, e, result, progressItem)
          })
          rs.push xhr.send(file)
      $.when.apply($,rs).done( self.options.onTransferFinished ) if self.options.onTransferFinished
