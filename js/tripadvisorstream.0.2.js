/*=== Tripadvisor Stream ===
Author: Pasquale Mangialavori
Plugin URI: http://pasqualemangialavori.netsons.org/tripadvisor-stream-wordpress-plugin
Tags: tripadvisor, hotels, holidays, vacations, restaurants
Requires at least: 3.0
Tested up to: 3.8.3
Stable tag: 0.1
Version: 0.1.1
License: GPL2 
Contributors:Pasquale Mangialavori

options you can use
          {
          minrate: 2, //typeof number max 10
          limit: 10, //typeof number reviews limit to show
          id:null, // typeof number id from tripadvisor url 
          lang:'it', // typeof string language 'it' | 'en'        
          sortby:'best' // typeof string  'best' | 'recent'
          }
*/
;(function($){
  $.fn.tripadvisor = function(options) {
    var that = this,        
        settings = {
          minrate: 2,
          limit: 10,
          id:null,
          lang:'it',          
          sortby:'best' //'best' | 'recent'
        };        
    options && $.extend(settings, options);

//string, json
    function putInSessionStorage(key,obj){
      if(sessionStorage){
        sessionStorage.setItem(key,JSON.stringify(obj));        
        return true;
      }else{
        return false;
      }
    }

    //string
    function getFromSessionStorage(key){
      if(sessionStorage && sessionStorage.getItem(key) != null){
        return JSON.parse(sessionStorage.getItem(key));
      }else{
        return false;
      }          
    }

    function loadTripScript(){
      var url = "http://www.tripadvisor.com/FeedsJS?f=hotels&defaultStyles=n&d="+settings.id+"&plang="+settings.lang;
      return $.getScript( url );        
    }


    function buildHTML(reviews){
            var string;
            console.log(reviews);
            that[0].innerHTML = '';
            //sort by rating 5,4,3
            
              if(settings.sortby == 'best'){
                reviews.sort(function(a,b){return  b.rating - a.rating});
              }else if(settings.sortby == 'recent'){
                //reviews.sort(function(a,b){return a.rating - b.rating});  
              }
            

            reviews.forEach(function(el,i){
              if(i < settings.limit){
              
              //build stars
              var stars = '';
                  for(j=0;j < el.rating;j++){
                      stars += '<i class="fa fa-star"></i>';
                  }

                string = "<div class='review'><a href='"+el.href+"' target='_blank'>"+el.title+"</a><div class='stars'>"+stars+"</div><div class='date'><i class='fa fa-calendar'></i>"+el.date+"</div><div class='user'><i class='fa fa-user'></i>"+el.user+"</div><div class='description'>"+el.description+"</div></div>";
                that.append(string);
              }
           });     
    }//buildHTML end

    function convertTables(){
          var reviews = [];
          var tmpreview;
          tablelist = that.children('table');

          //Object model
          function Review (title,href,date,user,description,rating) {
              this.title = title;
              this.href = href;
              this.date = date;
              this.user = user;
              this.description = description;
              this.rating = rating.split(' ')[0];//'3 of 5'
              return this;
          }
          
          //extract data from table nodes
          for (var i = 0; i < tablelist.length-2; i++) {
              //tablelistcopy[i] = tablelist[i];
              tmpreview = new Review(tablelist[i].querySelector('.TA_rname').innerHTML,
                           tablelist[i].querySelector('.TA_rname').href,
                           tablelist[i].querySelector('.TA_rdate').innerHTML,
                           tablelist[i].querySelector('.TA_ruser').innerHTML,
                           tablelist[i].querySelector('.TA_rdesc').innerHTML,
                           tablelist[i].querySelector('td img').alt);
              reviews.push(tmpreview);
          }
          putInSessionStorage('reviews',reviews);
          return reviews;
    }  

    function main(){
      //se c'è il session storage e ci sono le reviews

      if(getFromSessionStorage('reviews')){
        buildHTML(getFromSessionStorage('reviews'));
        console.log('hey!you are almost here! so i don\'t load the tripadvisor script');
        
        that.fadeIn();
        $('.overlayloader').slideUp();      
      }else{
        loadTripScript()
        .done(function( script, textStatus ){
                    console.log(textStatus);
                    that.fadeOut();
                    $('.overlayloader').fadeIn();
                    var ck = setInterval(function(){
                        if(that.children().length > 0){
                          console.log('now!');
                          buildHTML(convertTables());
                          
                          that.fadeIn();
                          $('.overlayloader').slideUp();
                          clearInterval(ck);
                        }else{
                          console.log('not yet...');}
                      },1000);         
                      
                      // override alert because tripadvisor always alert something sigh
                      window.realAlert = window.alert;
                      window.alert = function() {};
        })
        .fail(function( jqxhr, settings, exception ) {
                that.text( "Triggered ajaxError handler." );
        });
      }
      
    }//end main

    main();    
    return this;
  };
})(jQuery);

//initialize the plugin
jQuery('#TA_Container').tripadvisor({
		minrate:options.minrate,
		limit:options.limit,
		id:options.id,
    lang:options.lang,
    sortby:options.sortby
});