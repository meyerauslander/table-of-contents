//Author: Meyer Auslander
//java script envoked by WP on post pages
//1.  Loops through all the elements of the post and determines which ones get links to them in the toc.
//2.  Appends a link to the toc widget for every such element.
//3.  Sets link click action to be an animated scroll
//4.  Highlights links in the toc as the user scrolls up and down the page
(function($) { jQuery(document).ready(function(){
    var count = 0;  //heading count (used to give each a unique id)
    $('.post *').each(function(){
        var class1 = false;
        var class2 = false;
        var tag1  = false;
        var tag2  = false;
        var remove = false;
        var addit  = false;
        
        var type   = $(this).prop('tagName');
        type.toUpperCase(); //most versions of javascript are already in uppercase but maybe not all
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
        
        //this may be a sub-heading tag
        if ( ! addit ){
            for (i = 0; i < tags_2.length; i++) { 
                if (tags_2[i]==type){
                    tag2 = true;
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
            //add a class of trst_toc_item for the highlight item functionality
            $(this).addClass("trst_toc_item");
            
            if ( !class2 && (tag1 || class1)){ //don't indent it, tag1 precidence over tag2           
                var listLink = $('<a>') 
                            .attr('href', '#' + headlineAnchor)
                            .text(headlineVal)
                            .bind('click', scrollTo);
                var listItem = $('<li>').append(listLink);
            } else { //must be a class2 or a tag2.  indent it
                listLink = $('<a>') 
                            .attr('href', '#' + headlineAnchor)
                            .text(headlineVal)
                            .bind('click', scrollTo);
                listItem = $('<li>').append(listLink) 
                                    .attr('style', 'text-indent: 25px');
            }  
            $('.tstn_toc_list').append(listItem);   //append the link for this heading in the toc widget
        }  //end of add-it if statement
    });  //end of loop through all post elements
    
    makeHighlights();
}) //end of document.ready function
   
var makeHighlights = function(){
    // Initalize the ToC if we're on an article page
    if ($('.tstn_toc_list').length) {
        var toc = $('.tstn_toc_list');
        var tocOffset = toc.offset().top;
        var tocPadding = 0;  //need to check styles to determine padding

        var tocSections = $('.trst_toc_item');
        var tocSectionOffsets = [];

        // Calculates the toc section offsets, which can change as images get loaded
        var calculateTocSections = function(){
            tocSectionOffsets = [];
            tocSections.each(function(index, section){
                tocSectionOffsets.push($(section).offset().top);
            })
        }
        calculateTocSections();
        $(window).bind('load', calculateTocSections);

        var highlightTocSection = function(){
            var highlightIndex = 0;
            var foundIndex = false;         
            $.each(tocSectionOffsets, function(index, offset){
                //if (window.scrollY - highlight_offset >= offset - tocPadding){ //in my wordpress test page there as a 183 diffence between offsets and scrollY for each toc item
                if (window.scrollY >= offset - tocPadding){ 
                    foundIndex=true;                              //it's preferable to retreive this value from the css somewhere...
                    highlightIndex = index;
                }
            })
            highlightIndex += 1; //because the nth child begins with 1

            //deactive the old and reactivate the new    
            $('ul.tstn_toc_list .maus_active').removeClass('maus_active');         
            if (foundIndex) { //somtimes the first headline is not at the top of the page   
                $('ul.tstn_toc_list li:nth-child(' + highlightIndex + ') a').addClass('maus_active');
            }
        }
        highlightTocSection();

        var didScroll = false;
        $(window).scroll(function() {
            didScroll = true;
        })

        setInterval(function() {
            if (didScroll) {
                didScroll = false;
                //this is currently not doing anything because the user is setting the widget to be fixed in wordpress
                //in order to use this method instead update the style for maus_sticky in table-of-contents.php  
                if (window.scrollY > tocOffset - tocPadding)
                    toc.addClass('maus_sticky');
                else
                    toc.removeClass('maus_sticky');
            }
            highlightTocSection();
        }, 100);              
    } //end of the toc-has-length if statement
}  //end of make-highlights function       
              
              
//defines the functionality that will occur when one clicks one of the toc links
//do an animated scroll if 400 miliseconds ti the top if this href
var scrollTo = function(e) {
    e.preventDefault();
    var elScrollTo = $(e.target).attr('href');
    var $el = $(elScrollTo);
    var scroll_pos = $el.offset().top;
    $('html,body').animate({ scrollTop: scroll_pos }, 400, 'swing', function() {
        location.hash = elScrollTo;
    })
}

}) (jQuery); //end if jquery indentifier