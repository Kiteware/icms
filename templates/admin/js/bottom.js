/**
* Bottom JS
*/

$(".dropdown").on("click", function(e){
    if($(this).hasClass("open")) {
        $(this).removeClass("open");
        $(this).children("ul").slideUp("fast");
    } else {
        $(this).addClass("open");
        $(this).children("ul").slideDown("fast");
    }
});
$(".edit_menu").click(function () {
    $("div.hidden_menu").show("slow");
    $("#nav_name").prop('readonly', false);
    $( "#create" ).attr('name', 'create');
    $( "#submit" ).attr('value', 'create');
    document.getElementById("nav_name").value = "";
    document.getElementById("nav_link").value = "";
    document.getElementById("nav_position").value = "";
});
//callback handler for form submit
$("#menu_manager").submit(function(e) {
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data, textStatus, jqXHR)
            {
                //data: return data from server
                $('#hidden_menu').append(data);
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                //if fails
            }
        });
    e.preventDefault(); //STOP default action
});
function editNav(name, link, position) {
    $("div.hidden_menu").show("slow");

    document.getElementById("nav_name").value = name;
    document.getElementById("nav_link").value = link;
    document.getElementById("nav_position").value = position;
    document.getElementById("submit").value = "update";
    successAlert();
};
function deleteNav(nav_url) {
    $.ajax({
        type: 'POST',
        url: '/admin/pages/menu',
        data: { nav_link: nav_url, nav_delete: 'yes'}
    });
    successAlert();
  };
