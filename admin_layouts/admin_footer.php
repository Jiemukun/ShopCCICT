
<script>
      
    var timeoutDuration = 60 * 10 * 1000; 
    var logoutTimer;

    function resetTimer() {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(function () {
            alert('Your session has expired due to inactivity. You will be logged out.');
            window.location.href = 'logout.php?logout=1'; 
        }, timeoutDuration);
    }

   
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>