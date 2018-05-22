//Author: Meyer Auslander
//java script envoked by WP on post pages
//1.  Loops through all the elements of the post and determines which ones get links to them in the toc.
//2.  Appends a link to the toc widget for every such element.
(function($) { jQuery(document).ready(function(){
    var count = 0;  //heading count (used to give each a unique id)
    $('.post *').each(function(){
        var class1 = false;
        var class2 = false;
        var tag1  = false;
        var remove = false;
        var addit  = false;
        
        var type   = $(this).prop('tagName');
        var id     = $(this).attr('id');
        
        //check if it's a headline or sub headline class
        for (i = 0; i < classes_1.length; i++) { 
            if ( $(this).hasClass( classes_1[i] ) ){
                class1 = true;
                addit  = true;
                break;
            } 
        }
        //check if sub heading class
        for (i = 0; i < classes_2.length; i++) { 
            if ( $(this).hasClass( classes_2[i] ) ){
                class2 = true;
                addit  = true;
                break;
            } 
        }
        
        //console.log("The type is " + type); //for test purposes
        
        //check if this is one of the selected tags even if it doesn't have a toc class 
        //only check if a toc class name is not present (because class name takes presdence if there's a confilct)
        if ( ! addit ){
            for (i = 0; i < tags_1.length; i++) { 
                if (tags_1[i]==type){
                    tag1 = true;
                    addit  = true;
                } 
            }
        }
        
        if ($(this).hasClass( 'entry-title' )) remove = true; // we don't want a link to the title
        
        //add to the table of contents
        if ( addit && !remove){
            count++;
            var headlineVal    = $(this).text();                //the heading to appear in the toc
            if ( !id ){ //it has no id
                var headlineAnchor = "trst_toc_item_" + count;       //the unique id value for this heading
                $(this).attr('id', headlineAnchor); //set the id of this element in the post page. 
            }
            else{
                headlineAnchor = id;
            }
            
            if ( !class2 ){ //don't indent it           
                var listLink = $('<a>') 
                            .attr('href', '#' + headlineAnchor)
                            .text(headlineVal)
                            .bind('click', scrollTo);
                var listItem = $('<li>').append(listLink);
            } else { //must be class_name2.  indent it
                listLink = $('<a>') 
                            .attr('href', '#' + headlineAnchor)
                            .text(headlineVal)
                            .bind('click', scrollTo);
                listItem = $('<li>').append(listLink) 
                                    .attr('style', 'text-indent: 25px');
            }  
            $('.tstn_toc_list').append(listItem);   //append the link for this heading in the toc widget
        }  
    });
    
}) 
   
//defines the functionality that will occur when one clicks one of the toc links              
var scrollTo = function(e) {
    e.preventDefault();
    var elScrollTo = $(e.target).attr('href');
    var $el = $(elScrollTo);

    $('body,html').animate({ scrollTop: $el.offset().top }, 400, 'swing', function() {
        location.hash = elScrollTo;
    })
}

}) (jQuery);