(function($) {
  jQuery(document).ready(function(){
    $('.post h1, .post h2').each(function(){
      var headlineVal    = $(this).text(),
          headlineAnchor = headlineVal.replace(/[_ \t]+/g, '_'),
          liItem         = '<li><a href="#' + headlineAnchor.toLowerCase() + '">' + headlineVal + '</a></li>';
      $(this).attr('id', headlineAnchor.toLowerCase())
      $('.table_of_contents_list').append(liItem);
    });
  })
})(jQuery);