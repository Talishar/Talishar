
function OnLoadCallback(lastUpdate)
{
  reload();
  ReloadChat(lastUpdate);
}

function ShowCardDetail(e, that)
{
  ShowDetail(e, that.getElementsByTagName("IMG")[0].src);
}

function ShowDetail(e, imgSource)
{
  var el = document.getElementById("cardDetail");
  el.innerHTML = "<img style='height:523px; width:375px; position:absolute; z-index:1000;' src='" + imgSource + "' />"
  el.style.left = e.clientX < screen.width / 2 ? e.clientX + 30 : e.clientX - 300;
  el.style.top = e.clientY > screen.height / 2 ? e.clientY - 523 - 20 : e.clientY + 30;
  el.style.zIndex = 100;
  el.style.display = "inline";
  el.style.position = "fixed";
}

function HideCardDetail()
{
  var el = document.getElementById("cardDetail");
  el.style.display = "none";
}

function ChatKey(event)
{
  if(event.keyCode === 13) 
  {
    event.preventDefault();
    SubmitChat();
  }
}

function SubmitChat()
{
  var chatBox = document.getElementById("chatText");
  if(chatBox.value == "") return;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //alert(this.responseText);
      //if(this.responseText[1] == "1") { location.reload(); }
    }
  }
  var ajaxLink = "SubmitChat.php?gameName=" + document.getElementById("gameName").value;
  ajaxLink += "&playerID=" + document.getElementById("playerID").value + "&chatText=" + encodeURI(chatBox.value);
  xmlhttp.open("GET", ajaxLink, true);
  xmlhttp.send();
  chatBox.value = "";
}


function ReloadChat(lastUpdate)
{
  //var chatBox = document.getElementById("chatText");
  //if(chatBox.value == "") return;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      el = document.getElementById('gamelog');
      ReloadChat(this.responseText.substring(0, 11));
      var logText = this.responseText.slice(11);
      el.innerHTML = logText;
      el.scrollTop = el.scrollHeight;
    }
  }
  var ajaxLink = "ReloadChat.php?gameName=" + document.getElementById("gameName").value;
  ajaxLink += "&playerID=" + document.getElementById("playerID").value + "&lastUpdate=" + lastUpdate;
  xmlhttp.open("GET", ajaxLink, true);
  xmlhttp.send();
}