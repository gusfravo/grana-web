function isConnetion()
{
    // Rudimentary check to see if we are running on Heroku. Should provide a more flexible config.
    return window.location.hostname.indexOf('www.grana.mx') == 0;
}

export let SERVER_URL = isConnetion() ? "./php/" : "http://localhost:81/";
