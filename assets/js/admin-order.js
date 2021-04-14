(function( $ ) {
    if ( cpy_order_settings.hide_api_refund ) {
        let refund_button = $( '.refund-actions .do-api-refund' );
        refund_button.attr( 'disabled', true );
        refund_button.append( '<p style="color: red">将账户切换为' + cpy_order_settings.token_name + '后才可使用</p>' )
    }
})( jQuery );