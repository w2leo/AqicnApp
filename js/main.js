// Fill table script
function getData() {
	console.log("Table fill started");
	var xhr = new XMLHttpRequest();
	var url = "/?main=fill";
	xhr.open("GET", url, true);

	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			var request = xhr.responseText.split("JSON_TABLE");
			var json = request[1];
			var data = JSON.parse(json);
			var tableBody = document
				.getElementById("airTable")
				.getElementsByTagName("tbody")[0];
			tableBody.innerHTML = "";

			for (var i = 0; i < data.length; i++) {
				var item = data[i];
				var row = tableBody.insertRow();
				row.insertCell(0).innerHTML = item.city;
				row.insertCell(1).innerHTML = item.airData;
				row.insertCell(2).innerHTML =
					'<a href="#" class="delete-city">delete</button>';
			}
			showPage();
		}
	};
	xhr.send();
}

window.onpageshow = function () {
	getData();
};

// Delete city script
document.body.addEventListener("click", function (event) {
	debugger;
	if (event.target.classList.contains("delete-city")) {
		event.preventDefault();

		var keyFieldValue =
			event.target.parentNode.parentNode.querySelector(
				"td:first-child"
			).textContent;

		var formData = new FormData();
		formData.append("remove_city", keyFieldValue);

		var row = event.target.parentNode.parentNode;
		row.parentNode.removeChild(row);

		fetch("/", {
			method: "POST",
			body: formData
		}).then(function (response) {
			if (response.status === 200) {
				console.log("Request successful");
			} else {
				console.error("Request failed");
			}
		});
	}
});

function showPage() {
	document.getElementById("loader").style.display = "none";
	document.getElementById("hiddenDiv").style.display = "block";
}

function hidePage() {
	document.getElementById("loader").style.display = "block";
	document.getElementById("hiddenDiv").style.display = "none";
}
