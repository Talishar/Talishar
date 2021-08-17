
function ShowCardDetail(e, that)
{
  ShowDetail(e, that.getElementsByTagName("IMG")[0].src);
}

function ShowDetail(e, imgSource)
{
  var el = document.getElementById("cardDetail");
  el.innerHTML = "<img style='height:523px; width:375px; position:absolute; z-index:1000;' src='" + imgSource + "' />"
  el.style.left = e.clientX + 10;
  el.style.top = e.clientY - 523 - 10;
  el.style.zIndex = 100;
  el.style.display = "inline";
  el.style.position = "fixed";
}

function HideCardDetail()
{
  var el = document.getElementById("cardDetail");
  el.style.display = "none";
}