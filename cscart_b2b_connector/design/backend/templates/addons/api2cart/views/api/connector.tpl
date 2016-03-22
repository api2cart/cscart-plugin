{script src="js/addons/api2cart/api2cart.js"}
{style src="addons/api2cart/styles.css"}

{capture name="mainbox"}

  <div class="api2cart">
    <div class="message"></div>
    <div class="text-center">
      <a target="_blank" href="{$publicSiteUrl}">
        <img src="{$images_dir}/addons/api2cart/images/logo.png" alt="API2Cart - Unified Shopping Cart API Integration Interface">
      </a>
    </div>
    <div class="text-center">
      <p>{__("platform_to_integrate")}</p>
    </div>

    <div class="container">

      <div class="text-center">
        <p id="connector-installed-txt">{__("connector_installed_txt")}</p>
        <div id="content-block-manage">
          <b>{__("message_congratulations")}</b><br><br>
          <ul class="list-create-account">
            <li>{__("connect_info")}</li>
            <li>{__("connect_info_li1")}</li>
            <li>{__("connect_info_li2")}</li>
            <li>{__("connect_info_li3")}</li>
          </ul>
          <br>
          <b>{__("pleas_note_title")}</b>
          {__("pleas_note")}
          <br>
        </div>
      </div>

      <div class="text-center">

        <div class="progress progress-dark progress-small progress-striped active">
          <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="store-key">
          <span class="store-key-title">{__("test_store_key")}</span>
          <span class="store-key-content" id="storeKey">{$storeKey}</span>

          {include file="buttons/button.tpl" but_role="text" but_meta="btn-update-store-key" but_name="dispatch[api.connector]" but_text=__("admin_update") but_target_id="updateBridgeStoreKey"}

        </div>
        <br>
        {include file="buttons/button.tpl" but_role="text" but_meta="btn-disconnect btn-setup" but_name="dispatch[api.connector]" but_text=__("admin_disconnect") but_target_id="api2cartConnectionUninstall"}
        {include file="buttons/button.tpl" but_role="text" but_meta="btn-connect btn-setup" but_name="dispatch[api.connector]" but_text=__("admin_connect") but_target_id="api2cartConnectionInstall"}

      </div>

      <input type="hidden" id="showButton" value="{$showButton}">
      <div id="ajax-show-button-res">{$showButtonResult}<!--ajax-show-button-res--></div>

      <div class="clearfix"></div>
    </div>
  </div>

{/capture}

{include file="common/mainbox.tpl" title=__("api2cart_bridge_connector") content=$smarty.capture.mainbox}