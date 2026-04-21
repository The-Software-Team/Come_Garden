function getCSRF() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

function showMessage(type, message) {
    const box = document.getElementById("messageBox");

    if (!box) return;

    box.innerHTML = `
        <div class="alert ${type}">
            ${message}
        </div>
    `;
}

/**
 * Blade-style POST 
 */

async function postForm(url, data) {
    try {
        const formData = new URLSearchParams();

        for (const key in data) {
            formData.append(key, data[key]);
        }

        const response = await fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": getCSRF(),
                "Accept": "text/html"
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error("Request failed");
        }

        const html = await response.text();

        return {
            success: true,
            html: html
        };

    } catch (err) {
        return {
            success: false,
            error: "Network error"
        };
    }
}

/* =========================
   DEPOSIT
========================= */
document.getElementById("depositForm")?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const data = Object.fromEntries(new FormData(form));

    const result = await postForm(window.seedbank.depositUrl, data);

    if (result?.error) {
        showMessage("error", result.error);
        return;
    }

    showMessage("success", "Seeds deposited successfully!");

});


