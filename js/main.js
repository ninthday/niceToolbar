
/**
 * Show message on the web
 * @param {string} msgType message type（success、info、warning、danger）
 * @param {string} msgContent message content
 * @returns {undefined}
 */
function showMessage(msgType, msgContent) {
    $("#alert-message").removeClass().addClass("alert").addClass("alert-dismissible").addClass("alert-" + msgType);
    $("#alert-content").text('').text(msgContent);
    $("#alert-message").fadeIn(300).delay(6000).fadeOut(500);
};
