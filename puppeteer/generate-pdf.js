const puppeteer = require("puppeteer");
const fs = require("fs");
const path = require("path");

const url = process.argv[2];
const outputPath = process.argv[3];

(async () => {
    const browser = await puppeteer.launch({
        headless: "new",
        args: ["--no-sandbox"],
    });
    const page = await browser.newPage();

    await page.goto(url, { waitUntil: "networkidle0" });

    await page.pdf({
        path: outputPath,
        format: "A4",
        printBackground: true,
        margin: { top: "0", right: "0", bottom: "0", left: "0" },
    });

    await browser.close();
})();
