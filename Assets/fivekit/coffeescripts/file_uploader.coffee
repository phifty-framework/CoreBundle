
window.FiveKit = {} if typeof window.FiveKit is "undefined"

class FiveKit.FileUploader
  actionClass: "CoreBundle::Action::Html5Upload"

  constructor: (@config) ->
    @actionClass = @config.action if @config.action
    @progressContainer  = @config.progressContainer

  upload: (file) ->
    ActionCsrfToken.get success: (csrfToken) =>
      rs = @uploadFile(csrfToken, file)
      $.when.apply($, [rs]).done(@config.onTransferFinished ) if @config.onTransferFinished
