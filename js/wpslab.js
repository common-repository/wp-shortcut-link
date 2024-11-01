jQuery(document).ready(function ($) {

    var Catqury_data=[];
    var grididel; 
    var itemidel; 
    var responsiveid="lg"
    var is_update=0;
    var newPageNum=1;

    


if ($('#jsdata').length > 0 ) {

  var dd= $('#jsdata').val() ; 
  if (dd.length > 5) {
  Catqury_data =JSON.parse( dd); 
   Ces_Desine_Cat(Catqury_data);
 }
}


  function Ces_Desine_Cat(Catqury_data ) { 


    var DesineEl = $("#desine") ; 
         var html='' ; 
       for (var i = 0; i < Catqury_data.length; i++) {
        
             html+='<div class="cesrow hoverborder"  id="'+ Catqury_data[i].grid+'" >'
             html+='<span class="removeitem" gridid="'+ Catqury_data[i].grid+'">x</span>'
              
              var childs = Catqury_data[i].childs ;
              var heightels = Catqury_data[i].height ;

            

              for (var j = 0; j < childs.length; j++) {

              var classel ="ceslg-"+ childs[j].collg+" cesmd-"+ childs[j].colmd+" cessm-"+ childs[j].colsm+" cesxs-"+childs[j].colxs; 


                 var style = "font-size:"+childs[j].fontsize+"px;color:"+childs[j].color+";height:"+heightels+"px;line-height:"+heightels+"px;"; 
                 if (childs[j].bgtype=="color") {style+="background:"+childs[j].bg+";"}
                 if (childs[j].bgtype=="img") {style+="background-image:url('"+childs[j].bg+"');background-size: 100% 100%;background-repeat: no-repeat;"}

                 html+= '<div id="'+childs[j].id+'" class="'+classel+'"><div class="cesitem" style="'+style+'"><span class="cesedit" gridid="'+ Catqury_data[i].grid+'" elid="'+childs[j].id+'" ></span><span class="textcontent">'+childs[j].textcontent+'</span></div></div>';
                
              }

       // html+='<span  gridid="'+ Catqury_data[i].grid+'" class="duplicate">duplicate+</span> </div>';     
            
       }is_update=1;
     DesineEl.html(html);

    }

    

    $("#savecat").click(function () {

      var jsdata= JSON.stringify(Catqury_data);
      var title= $("#titlecat").val();
      var id_wpslab= $("#id_wpslab").val();
      $(".spinner").addClass("is-active");

      if (!title) {alert("Title is empty")} 
        if (title) {

      var data = {
            action: 'save_wpslab_data',
            title: title,
            jsdata:  jsdata,
            id_wpslab:id_wpslab
           
           // nonce:nonce
        };
        $.post(the_in_url.in_url, data, function(response) {
            var res = $.parseJSON(response);
           $(".spinner").removeClass("is-active");

        });
      }



    });


    $(".imggrid").click(function () {

       $(".imggrid").removeClass("cesactive") ; 
       $(this).addClass("cesactive") ; 

        var numgrid = parseInt( $(this).attr("numgrid"));
        var grid = $(this).attr("grid").split("-");

         
         var childs = []; 

         var ll = Catqury_data.length+1

          

        for (var i = 0; i < numgrid; i++) {

          childs.push({
                          collg:grid[i],
                          colmd:grid[i], 
                          colsm:grid[i], 
                          colxs:12,
                          id:"catces"+i+"-"+ll,
                          textcontent:"Link "+(i+1),
                          bgtype:"color",
                          bg:"#7B1FA2",
                          color:"#fff",
                          fontsize:26,
                          link:"https://"
                        })  
          
        }Catqury_data.push({ grid:"cesgrid-"+ll,height:150,childs:childs});

       


      Ces_Desine_Cat(Catqury_data);
    })


    function create_UUID(){
    var dt = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random()*16)%16 | 0;
        dt = Math.floor(dt/16);
        return (c=='x' ? r :(r&0x3|0x8)).toString(16);
    });
    return uuid;
}


      $("body").on("click", '.cesedit', function () {

        $(".modalces").css({display: "block" });

         grididel=$(this).attr("gridid"); 
         itemidel=$(this).attr("elid");


       Get_Edit_Item() ; 

       });
      $("body").on("click", '.duplicate', function () {

          var cugrididel=$(this).attr("gridid"); 
          var newrow=[]; 
          var newchilds=[]; 
         var ll = Catqury_data.length+1



          for (var i = 0; i < Catqury_data.length; i++) {
        
           if (Catqury_data[i].grid==cugrididel) 
           
           {

            newrow = Catqury_data[i]; 
            for (var i = 0; i < newrow.childs.length; i++) {

              newchilds[i]=newrow.childs[i] ; 
              newchilds[i].id=create_UUID() 

            }
      

           }

       }
        

            Catqury_data.push({ grid:"cesgrid-"+ll,height:newrow.height,childs:newchilds});
           Ces_Desine_Cat(Catqury_data)

      

       });



      $(".cesradio").click(function () {

        var link = $(this).val() ; 
        Update_Child_item({link:link});
        $("#ceslink").val(link);

      })

      $("#changehight").change(function () {

        var val = $(this).val() ; 
        Update_Catquey_item({height:val})

      })      
      $("#chanfontsize").change(function () {

        var val = $(this).val() ;
      Update_Child_item({fontsize:val}) ;
      })
      $("#changcolor").change(function () {


        var valcolor = $(this).val() ; 
      Update_Child_item({color:valcolor}) ;
      })

      $(".gridresponsive").change(function () {

        var val = $(this).val() ; 
        var id = $(this).attr("id") ; 


        switch(id)
                  {
                    case "collg":
                    
                    Update_Child_item({collg:val}) ;

                    break;

                    case "colmd":
                    Update_Child_item({colmd:val}) ;
                    break; 

                    case "colsm":
                    Update_Child_item({colsm:val}) ;
                    break; 

                    case "colxs":
                    Update_Child_item({colxs:val}) ;
                    break;  
                  }





      })  ; 


      $(".cescolorels span").click(function () {

          var bgtype=$(this).attr("type"); 
          var  bgval=$(this).attr("bgval");

         if (bgtype=="color") {$("#cescolorbox").css({background:bgval})}



          Update_Child_item({bgtype:bgtype,bg:bgval}) ;






      });


      $("body").on("click", '.removeitem', function () {
          var gridid=$(this).attr("gridid");

          RemoveItem(gridid) ;  

      
      });


      function RemoveItem(idgrid) 
      {
         
         for (var i = 0; i < Catqury_data.length; i++) {
        
           if (Catqury_data[i].grid==idgrid) 
           
           {
            
            Catqury_data.splice(i, 1);

           }

       }
       
        Ces_Desine_Cat(Catqury_data)
        }

      function Update_Catquey_item(itemdata) 

        {
         for (var i = 0; i < Catqury_data.length; i++) {
        
           if (Catqury_data[i].grid==grididel) 
           
           {
             Catqury_data[i].height=itemdata.height;

           }

       }
        Ces_Desine_Cat(Catqury_data)
        }



      function  Get_Edit_Item() 

        {
         for (var i = 0; i < Catqury_data.length; i++) {
        
           if (Catqury_data[i].grid==grididel) 
           {


              var childs = Catqury_data[i].childs ;

             

              for (var j = 0; j < childs.length; j++) {

                if (childs[j].id==itemidel) 
                {

                  $("#cestextarea").val(childs[j].textcontent); 
                  $("#chanfontsize").val(childs[j].fontsize); 
                  $("#changehight").val(Catqury_data[i].height); 
                  $("#changcolor").val(childs[j].color); 
                  $("#ceslink").val(childs[j].link); 
                  $("input[value='"+childs[j].link+"']").attr('checked', true);

                  if (childs[j].bgtype=="color") {$("#cescolorbox").css({background:childs[j].bg})}

                 
                    $("#collg").val(childs[j].collg)
                    
                    $("#colmd").val(childs[j].colmd)
                  
                    $("#colsm").val(childs[j].colsm)
                    
                    $("#colxs").val(childs[j].colxs)
                   


                }
              }
          }
         }

        }


      function Update_Child_item(itemdata) 

      {

       

           for (var i = 0; i < Catqury_data.length; i++) {
        
           if (Catqury_data[i].grid==grididel) 
           {


              var childs = Catqury_data[i].childs ;

             

              for (var j = 0; j < childs.length; j++) {

                if (childs[j].id==itemidel) 
                {

         Object.assign( Catqury_data[i].childs[j],itemdata)


                    
         

           }
         }
       }


      }
                            

      Ces_Desine_Cat(Catqury_data)
  }


      $("body").on("click", '.closeces', function () {


          $(".modalces").css({
            display: "none"
          });

  });



$("#ceslink").on('change keyup paste', function() {

  var link = $(this).val();

 Update_Child_item({link:link}) ;
    });


 function insertAtCaret(areaId, text) {
  var txtarea = document.getElementById(areaId);
  if (!txtarea) {
    return;
  }

  var scrollPos = txtarea.scrollTop;
  var strPos = 0;
  var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
    "ff" : (document.selection ? "ie" : false));
  if (br == "ie") {
    txtarea.focus();
    var range = document.selection.createRange();
    range.moveStart('character', -txtarea.value.length);
    strPos = range.text.length;
  } else if (br == "ff") {
    strPos = txtarea.selectionStart;
  }

  var front = (txtarea.value).substring(0, strPos);
  var back = (txtarea.value).substring(strPos, txtarea.value.length);
  txtarea.value = front + text + back;
  strPos = strPos + text.length;
  if (br == "ie") {
    txtarea.focus();
    var ieRange = document.selection.createRange();
    ieRange.moveStart('character', -txtarea.value.length);
    ieRange.moveStart('character', strPos);
    ieRange.moveEnd('character', 0);
    ieRange.select();
  } else if (br == "ff") {
    txtarea.selectionStart = strPos;
    txtarea.selectionEnd = strPos;
    txtarea.focus();
  }

  txtarea.scrollTop = scrollPos;
}


  $(".cesemojilist li").click(function () {

    var trxt =$(this).text() ;

    insertAtCaret("cestextarea",trxt)  ; 

      $("#cestextarea").change() ;     
        


  }) ; 

iframedesine();
  $(".imgresponsive").click(function () {

    iframedesine();

    var w = $(this).attr("data-w") ; 
    responsiveid = $(this).attr("device") ;

    $(".imgresponsive").removeClass("cesactive") ; 
     $(this).addClass("cesactive") ;
     $("iframe").css({width:w+"px"}) ; 
     $("#boxembed").css({width:w+"px"}) ; 

/*
    if (is_update==1) {

      var srcemded= $("embed").attr("src");
      var srcemded = srcemded.replace("dd="+newPageNum, "dd="+(newPageNum+1));

      var jsdata= JSON.stringify(Catqury_data);
      var title= $("#titlecat").val();
      var id_wpslab= $("#id_wpslab").val();
      $(".spinner").addClass("is-active"); 

      var el=$(this);

      var data = {
            action: 'save_wpslab_data',
            title: title,
            jsdata:  jsdata,
            id_wpslab:id_wpslab
           
           // nonce:nonce
        };
        $.post(the_in_url.in_url, data, function(response) {
            var res = $.parseJSON(response);
           $(".spinner").removeClass("is-active");
           is_update=0;

           
           $(".imgresponsive").removeClass("cesactive") ; 
          el.addClass("cesactive") ;
           //$("embed").css({width:w+"px"}) ; 
           $("#boxembed").css({width:w+"px"}) ;
           $("#embedparent").html('<embed width="'+w+'" height="600" src="'+srcemded+'">')

        });

      }
    if (is_update==0){
   
     $(".imgresponsive").removeClass("cesactive") ; 
     $(this).addClass("cesactive") ;
     $("embed").css({width:w+"px"}) ; 
     $("#boxembed").css({width:w+"px"}) ; 
   }

*/
        


  }) ;


  function iframedesine() 
  {
    var iframe = document.getElementById('embed');
    var htmldesine = $("#desine").html();
    //htmldesine = htmldesine.replace(new RegExp("removeitem", "g"), 'removeitem none') ;
     

          var html='<!DOCTYPE html> ';
            html+='<html>';
            html+='<head>';
            html+=' <meta charset="utf-8">';
             html+=' <title>Demo</title>';
            
           html+='  <link rel="stylesheet" type="text/css" href="'+iframe.getAttribute("s")+'/css/embeds.css"/>';
            html+='  </head>';
             html+=' <body>';
            html+=htmldesine;

             html+= '</body>';
            html+= '</html>';

   // iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(html);

var $iframe = $('#embed');
   $iframe.ready(function() {
   $iframe.contents().find("body").html(html);
   //$iframe.contents().find(".removeitem").remove(html);
  // $iframe.contents().find(".cesedit").remove(html);
});



    
  } 

    $("#cestextarea").on('change keyup paste', function() {

        var val = $(this).val() ; 

       Update_Child_item({textcontent:val}) ;

        //

      });




   function Setphoto(imageurl) {



   var img = new Image();
   img.onload = function() {
       Update_Child_item({bgtype:"img",bg:imageurl}) ;
       Update_Catquey_item({height:this.height})
     }
     img.src = imageurl;




   }






   if ($('.set_custom_images').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $('.set_custom_images').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
               
               
                wp.media.editor.send.attachment = function(props, attachment) {
                   
                  Setphoto(attachment.url);

                };
                 wp.media.editor.open(button);
                return false;
            });
        }
    }




  });