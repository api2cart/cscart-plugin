jQuery(document).ready(function($) {
  var messages = $('#messages');

  var installationsText = $('#connector-installed-txt');
  var contentBlockManage = $('#content-block-manage');

  var showButton = $("#showButton");
  var bridgeStoreKey = $('#bridgeStoreKey');
  var storeKey = $('#storeKey');
  var storeBlock = $('.store-key');
  var classMessage = $('.message');
  var progress = $('.progress');

  var timeDelay = 500;

  var api2cartConnectionInstall = $(".btn-connect");
  var api2cartConnectionUninstall = $(".btn-disconnect");

  var updateBridgeStoreKey = $('.btn-update-store-key');

  if (showButton.val() == 'install') {
    installationsText.show();
    contentBlockManage.hide();
    storeBlock.fadeOut();
    updateBridgeStoreKey.hide();
    api2cartConnectionUninstall.hide();
    api2cartConnectionInstall.show();
  } else {
    installationsText.hide();
    contentBlockManage.show();
    storeBlock.fadeIn();
    updateBridgeStoreKey.show();
    api2cartConnectionInstall.hide();
    api2cartConnectionUninstall.show();
  }

  function message(message,status) {
    if (status == 'success') {
      classMessage.html('<span>' + message + '</span>');
      classMessage.fadeIn("slow");
      classMessage.fadeOut(5000);
      var messageClear = setTimeout(function(){
        classMessage.html('');
      }, 3000);
      clearTimeout(messageClear);
    }
  }

  $('.btn-setup').click(function() {
    var self = $(this);
    $(this).attr("disabled", true);
    progress.slideDown("fast");
    var install = 'install';
    if (showButton.val() == 'uninstall') {
      install = 'remove';
    }

    var url = fn_url("api.ajaxConnector");
    $.ceAjax('request', url, {
      data: {
        type: install+'Bridge',
        addon: 'api2cart',
        result_ids: 'ajax-show-button-res'
      },
      callback: function() {
        var found = $('#ajax-show-button-res').html();
        var json = JSON.parse(found);

        self.attr("disabled", false);
        progress.slideUp("fast");

        if (json.install) {
          updateStoreKey(json.storeKey);
          installationsText.fadeOut(timeDelay);
          contentBlockManage.delay(timeDelay).fadeIn(timeDelay);
          storeBlock.fadeIn("slow");
          updateBridgeStoreKey.fadeIn("slow");
          showButton.val('uninstall');
          api2cartConnectionInstall.hide();
          api2cartConnectionUninstall.show();
          message('Connector Installed Successfully','success');
        } else {
          contentBlockManage.fadeOut(timeDelay);
          installationsText.delay(timeDelay).fadeIn(timeDelay);
          storeBlock.fadeOut("slow");
          updateBridgeStoreKey.fadeOut("slow");
          showButton.val('install');
          api2cartConnectionUninstall.hide();
          api2cartConnectionInstall.show();
          message('Connector Uninstalled Successfully','success');
        }
      }
    });
  });

  updateBridgeStoreKey.click(function(){
    var url = fn_url("api.ajaxConnector");
    $.ceAjax('request', url, {
      data: {
        type: 'updateToken',
        addon: 'api2cart',
        result_ids: 'ajax-show-button-res'
      },
      callback: function () {
        var found = $('#ajax-show-button-res').html();
        var json = JSON.parse(found);

        updateStoreKey(json.storeKey);
        message('Connector Updated Successfully!', 'success');
      }
    });
  });

  function updateStoreKey(data){
    storeKey.html(data);
  }

});
