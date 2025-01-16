var _openPopup = null;

function OnLoadCallback(lastUpdate) {
  var log = document.getElementById("gamelog");
  if (log !== null) log.scrollTop = log.scrollHeight;
  reload();
}

function ShowCardDetail(e, that) {
  ShowDetail(e, that.getElementsByTagName("IMG")[0].src);
}

function ShowDetail(e, imgSource) {
  imgSource = imgSource.replace("_cropped", "");
  imgSource = imgSource.replace("/crops/", "/WebpImages/");
  imgSource = imgSource.replace("_concat", "");
  imgSource = imgSource.replace("/concat/", "/WebpImages/");
  imgSource = imgSource.replace(".png", ".webp");
  var el = document.getElementById("cardDetail");
  el.innerHTML =
    "<img style='height:523px; width:375px;' src='" + imgSource + "' />";
  el.style.left =
    e.clientX < window.innerWidth / 2 ? e.clientX + 30 : e.clientX - 400;
  el.style.top =
    e.clientY > window.innerHeight / 2 ? e.clientY - 523 - 20 : e.clientY + 30;
  if (parseInt(el.style.top) + 523 >= window.innerHeight) {
    el.style.top = window.innerHeight - 530;
    el.style.left =
      e.clientX < window.innerWidth / 2 ? e.clientX + 30 : e.clientX - 400;
  } else if (parseInt(el.style.top) <= 0) {
    el.style.top = 5;
    el.style.left =
      e.clientX < window.innerWidth / 2 ? e.clientX + 30 : e.clientX - 400;
  }
  el.style.zIndex = 100000;
  el.style.display = "inline";
}

function HideCardDetail() {
  var el = document.getElementById("cardDetail");
  el.style.display = "none";
}

function ChatKey(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
  }
  event.stopPropagation();
}

function AddCardToHand() {
  var card = document.getElementById("manualAddCardToHand").value;
  SubmitInput(10011, "&cardID=" + card);
}

function SubmitInput(mode, params, fullRefresh = false) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (fullRefresh) location.reload();
    }
  };
  var ajaxLink =
    "ProcessInput.php?gameName=" + document.getElementById("gameName").value;
  ajaxLink += "&playerID=" + document.getElementById("playerID").value;
  ajaxLink += "&authKey=" + document.getElementById("authKey").value;
  ajaxLink += "&mode=" + mode;
  ajaxLink += params;
  xmlhttp.open("GET", ajaxLink, true);
  xmlhttp.send();
}