jQuery(document).ready(function($) {

    var lastClass;
    var dataces ; 
    var ulces ; 
    var id_wpslab_to_save;


    var data = {
            action: 'wpslab_get_list_cat_client',
             url: $(".wpslabdata").attr("data-url"),

           
           // nonce:nonce
        };
        $.post(the_in_url.in_url, data, function(response) {
          dataces = $.parseJSON(response).wpslab;
         var  wpslab_client = $.parseJSON(response).wpslab_client;

         
          ulces="<select class='cesselect'>" ; 
        for (var i = 0; i < dataces.length; i++) 

        	{

          

                ulces+="<option  value='"+dataces[i].id_wpslab+"' >"+dataces[i].title+"</option >" ;
        	 } 
                ulces+="</select><button type='button' class='savewpslab cesbtn cesbtnprimary'><div  class='cesloader'>...</div> save</button>" ;
        	   ulces+="</ul>" ; 


        	 if (wpslab_client.length > 0) {

             var is_edit =parseInt( $(".wpslabdata").attr("edit") ); 
        	for (var j = 0; j < wpslab_client.length; j++) 

        	{

            	Ces_Desine_Cat_after(
            	     $.parseJSON(wpslab_client[j].jsdata ),
            		 wpslab_client[j].id_wpslab,wpslab_client[j].classel , 
            		 is_edit
            		 );


        	}

        }

        })
  

    $("div,section,header,nav").each(function(index, element) {

        $(element).addClass("wpslabtop wpslab" + index);
    });

    $("body").on("hover", '.wpslabtop', function (evt) {
         if (evt.ctrlKey)

        {
        $(".wpslabbyjsdesine").remove();
        var target = $(event.target);
        lastClass = target.attr("class").split(' ').pop();

    	 var html = '<div class="wpslabbyjs wpslabbyjsdesine" > ';
             html+="<ul classel='"+lastClass+"' dataid='"+lastClass+"'>"+ulces; 
    	    html+= '<div class="wpslabbody" id="'+lastClass+'" > </div>';
            html += '</div>';

          
            $(html).insertAfter("." + lastClass)

           }

    });





    $("body").on("change", '.cesselect', function (evt) {

    	 id_wpslab_to_save = $(this).val() ;
    	 var dataid = $(this).parent().attr("dataid");

      for (var i = 0; i < dataces.length; i++) 

        	{
            
            if (id_wpslab_to_save==dataces[i].id_wpslab) 
            {
            	
            	Ces_Desine_Cat( $.parseJSON(dataces[i].jsdata ),id_wpslab_to_save,$("#"+dataid));
            }
    	   

    }

    });

    $("body").on("click", '.removeitem', function () {

        var url = $(".wpslabdata").attr("data-url");
        var nonce=$(".wpslabdata").attr("nonce");
        var id_wpslab = $(this).attr("gridid") ; 

         var data = {
            action: 'remove_wpslab_dataclient',
            url: url,
            nonce:nonce,
            id_wpslab:id_wpslab
        };

        $.post(the_in_url.in_url, data, function(response) {
            var res = $.parseJSON(response);

            if (res.status==200) 
            {
              $("#wpslab"+id_wpslab).remove(); 
            }

        });


     
     });



    $("body").on("click", '.savewpslab', function (evt) {
        var url = $(".wpslabdata").attr("data-url");
        var nonce=$(".wpslabdata").attr("nonce");
        $(".cesloader").show();
        var classel= $(this).parent().attr("classel"); 


      var data = {
            action: 'save_wpslab_dataclient',
            url: url,
            lastClass: classel,
            nonce:nonce,
            id_wpslab:id_wpslab_to_save
        };

        $.post(the_in_url.in_url, data, function(response) {
            var res = $.parseJSON(response);
            $(".cesloader").hide();

        });


    });
    function Ces_Desine_Cat(Catqury_data,id_wpslab,DesineEl ) { 

 
   
         var html='<span class="removeitem" gridid="'+ id_wpslab+'">x</span>' ; 
       for (var i = 0; i < Catqury_data.length; i++) {
        
             html+='<div class="cesrow" id="wpslab'+id_wpslab+'"  >'
              
              var childs = Catqury_data[i].childs ;
              var heightels = Catqury_data[i].height ;



            

              for (var j = 0; j < childs.length; j++) {

              var classel ="ceslg-"+ childs[j].collg+" cesmd-"+ childs[j].colmd+" cessm-"+ childs[j].colsm+" cesxs-"+childs[j].colxs; 


                 var style = "font-size:"+childs[j].fontsize+"px;color:"+childs[j].color+";height:"+heightels+"px;line-height:"+heightels+"px;"; 
                 if (childs[j].bgtype=="color") {style+="background:"+childs[j].bg+";"}
                 if (childs[j].bgtype=="img") {style+="background-image:url('"+childs[j].bg+"');background-size: 100% 100%;background-repeat: no-repeat;"}

                 html+= '<a href="'+childs[j].link+'" class="'+classel+'"  ><div class="cesitem" style="'+style+'"><span class="cesedit" <span class="textcontent">'+childs[j].textcontent+'</span></div></a>';
                
              }

            
       }
     DesineEl.html(html);

    }


    function Ces_Desine_Cat_after(Catqury_data,id_wpslab,Classel,is_edit ) { 

 
       var html='<div class="wpslabbyjs" id="wpslab'+id_wpslab+'" >';
       if (is_edit) {
             html+="<ul classel='"+Classel+"' dataid='wpslab"+id_wpslab+"body'>"+ulces; 

          html+='<span class="removeitem" gridid="'+ id_wpslab+'">x</span>' ; 
      }

    	    html+= '<div class="wpslabbody" id="wpslab'+id_wpslab+'body" >';
       for (var i = 0; i < Catqury_data.length; i++) {
        
             html+='<div class="cesrow"  >'
              
              var childs = Catqury_data[i].childs ;
              var heightels = Catqury_data[i].height ;



            

              for (var j = 0; j < childs.length; j++) {

              var classel ="ceslg-"+ childs[j].collg+" cesmd-"+ childs[j].colmd+" cessm-"+ childs[j].colsm+" cesxs-"+childs[j].colxs; 


                 var style = "font-size:"+childs[j].fontsize+"px;color:"+childs[j].color+";height:"+heightels+"px;line-height:"+heightels+"px;"; 
                 if (childs[j].bgtype=="color") {style+="background:"+childs[j].bg+";"}
                 if (childs[j].bgtype=="img") {style+="background-image:url('"+childs[j].bg+"');background-size: 100% 100%;background-repeat: no-repeat;"}

                 html+= '<a href="'+childs[j].link+'" class="'+classel+'"  ><div class="cesitem" style="'+style+'"><span class="cesedit" <span class="textcontent">'+childs[j].textcontent+'</span></div></a>';
                
              }

            
       }html+='</div></div>';

            $(html).insertAfter("." + Classel)

    }



  });