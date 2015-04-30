<script type="text/javascript">
window._url = {
   'inquiry': {
        'all': '{{ route( 'messages.inquiries' ) }}',
        'find': '{{ route( 'inquiry', '_INQUIRY_ID_' ) }}',
        'conversations': '{{ route( 'inquiry.conversations', '_INQUIRY_ID_' ) }}',
        'reply': '{{ route( 'inquiry.reply' ) }}',
        'delivered': '{{ route( 'inquiry.delivered' ) }}',
        'reserve': '{{ route( 'inquiry.reserve' ) }}',
        'replyReceipt': '{{ route( 'inquiry.replyReceipt' ) }}'
        @if ( Auth::user()->isPermitted() )
        ,'byProduct': '{{ route( 'inquiry.product', '_INQUIRY_ID_' ) }}'
        ,'receiptAllowness': '{{ route( 'inquiry.receipt' ) }}'
        @endif
   },
   'inbox': {
        'all': '{{ route( 'messages.inbox' ) }}',
        'conversations': '{{ route( 'inbox.conversations', '_INQUIRY_ID_' ) }}',
        'find': '{{ route( 'inbox', '_INQUIRY_ID_' ) }}',
        'reply': '{{ route( 'inbox.reply' ) }}'
        @if( Auth::user()->isAdmin() )
        ,'removeConversation': '{{ route( 'inbox.remove.conversation', '_CONVERSATION_ID_' ) }}'
        @endif
   },
   'settings': {
        'unsubscribeNewsletter': '{{ route( 'settings.newsletter.unsubscribe' ) }}'
        @if ( Auth::user()->isAdmin() )
        ,'users': '{{ route( 'me.friends' ) }}'
        ,'permissions': '{{ route ( 'settings.permissions' ) }}'
        ,'general': '{{ route( 'settings.general' ) }}'
        ,'banner': '{{ route( 'settings.banner' ) }}'
        ,'deleteBanner': '{{ route( 'settings.banner.delete', '_BANNER_ID_' ) }}'
        @endif
   }
@if ( Auth::user()->isPermitted() )
  ,'deliver': {
    'all': '{{ route( 'messages.delivered' ) }}',
    'find': '{{ route( 'delivered', '_DELIVER_ID_' ) }}',
    'conversations': '{{ route( 'delivered.conversations', '_DELIVER_ID_' ) }}',
    'reply': '{{ route( 'delivered.reply' ) }}'
  }
  ,'products': {
    'all': '{{ route( 'products.index' ) }}',
    'updateCategory': '{{ route( 'product.update.category', '_PRODUCT_ID_' ) }}',
    'update': '{{ route( 'product.update', '_PRODUCT_ID_' ) }}',
    'destroy': '{{ route( 'product.destroy', '_PRODUCT_ID_' ) }}',
    'updateBadge': '{{ route( 'product.update.badge', '_PRODUCT_ID_' ) }}'
  }
  ,'reviews': {
    'all': '{{ route( 'reviews.index' ) }}',
    'approved': '{{ route( 'reviews.approved', '_REVIEW_ID_' ) }}',
    'unapproved': '{{ route( 'reviews.unapproved', '_REVIEW_ID_' ) }}'
  }
@endif
}
</script>
