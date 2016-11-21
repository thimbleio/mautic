Mautic.messagesOnLoad = function(container) {
  mQuery(container + ' .sortable-panel-wrapper .modal').each(function() {
      // Move modals outside of the wrapper
      mQuery(this).closest('.panel').append(mQuery(this));
  })
};