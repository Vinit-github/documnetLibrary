require(['jquery','mage/url'],function($,url){
    $(document).ready(function() {
        $("#search-documents").click(function(event){
            event.preventDefault();
            getDocumentList();             
        });
        $("#clear-button").click(function(event){
            event.preventDefault();
            $("#search-keyword").val(''); 
            $("#manufacturer").val(''); 
            $("#equipment_type").val(''); 
            $("#label").val(''); 
            getDocumentList();             
        });

    });

    function getDocumentList(){
            var keyword = $("#search-keyword").val(); 
            var manufactures = $("#manufacturer").val(); 
            var equpType = $("#equipment_type").val(); 
            var document = $("#label").val(); 
            url.setBaseUrl(BASE_URL);         
            var resultUrl = url.build('documentlist/list/searchresult');
            $('body').trigger('processStart');
            if(keyword || manufactures || equpType || document){
                $('.clear-button').css('display','block');
            }else{
                $('.clear-button').css('display','none');
            }
            jQuery.ajax({
            url: resultUrl,
            type: "POST",
            data: {isAjax: true,keyword:keyword,manufacturer:manufactures,label:document,equipment_type:equpType},
            showLoader: true,
            cache: false,
            success: function(response){
                console.log(response);
                $('.search-result').html(response.content);
                $('body').trigger('processStop');
            }
        });
    }
});
