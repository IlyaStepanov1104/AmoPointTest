fetch('add_statistic.php', {method: 'POST'})
    .then(response => {
        if (!response.ok) console.error(new Error("Network response was not ok"));
        else return response.json();
    }).then(data => console.log(data));