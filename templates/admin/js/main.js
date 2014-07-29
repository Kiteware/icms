$(".dropdown").on("click", function(e){
 // e.preventDefault();
  
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
    document.getElementById("nav_permission").value = "";
});
//callback handler for form submit
$("#menu_manager").submit(function(e)
{
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
 function editNav(name, link, position, permission) {
    $("div.hidden_menu").show("slow");
    $("#nav_name").prop('readonly', true);
    $( "#create" ).attr('name', 'update');
    $( "#submit" ).attr('value', 'update');
    document.getElementById("nav_name").value = name;
    document.getElementById("nav_link").value = link;
    document.getElementById("nav_position").value = position;
    document.getElementById("nav_permission").value = permission;
 }
 function deleteNav(name) {
 $.ajax({
  type: 'POST',
  url: 'edit_menu.php',
  data: { nav_name: name, delete: 'yes' }
 });
     location.reload();
 }
