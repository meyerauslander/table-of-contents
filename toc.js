//Author: Meyer Auslander
//java script envoked by WP on post pages
//1.  Sets the id for every <span> element having a class of trst_toc_heading_1 or trst_toc_heading_2
//to a unique value in order to make a link to it from the toc widget.
//2.  Appends a link to the toc widget for every such element.
(function($) { jQuery(document).ready(function(){
    var count = 0;  //heading count (used to give each a unique id)
    var class_name1 = "trst_toc_heading_1";
    var class_name2 = "trst_toc_heading_2";
    $('.post *').each(function(){
        if ( $(this).hasClass( class_name1 ) || $(this).hasClass( class_name2 ) ){
            count++;
            var headlineVal    = $(this).text(),                //the heading to appear in the toc
            headlineAnchor = "trst_toc_item_" + count;       //the unique id value for this heading  
            if ($(this).hasClass(class_name1)){ //don't indent it
                liItem = '<li><a href="#' + headlineAnchor + '">' + headlineVal + '</a></li>';  //set a variable for the link to this heading
            } else { //must be class_name2.  indent it
                liItem = '<li style=' + '"text-indent: 25px"' + '><a href="#' + headlineAnchor + '">' + headlineVal + '</a></li>';  //set the link to this heading
            }  
            $(this).attr('id', headlineAnchor)                  //set the id of this span in the post page
            $('.tstn_toc_list').append(liItem);   //append the link for this heading in the toc widget
        }      
    });
}) })(jQuery);