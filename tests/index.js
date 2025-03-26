const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    await page.goto('https://localhost', {waitUntil: 'networkidle2'});

    await page.$eval('#longUrl', (el , value) => el.value = value , "test.com");



    await browser.close();
})();