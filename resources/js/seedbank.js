function getCSRF() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

function showMessage(type, message, params=null) {
    const box = document.getElementById("messageBox");

    if (!box) return;
    if (params) {
        message += `<p> Batch ID: ${params.batch_id}, Credits Added: ${params.credits_added}</p>`;
    }
    box.innerHTML = `
        <div class="alert ${type}">
            ${message}
        </div>
    `;
}

async function postForm(url, data) {
    try {
       console.log(url);      
       const response = await fetch(url, 
        {
            method: "POST", 
            body: JSON.stringify(data), 
            headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": getCSRF()}
        })
        
        if (!response.ok) {
            throw new Error("Request failed");
        }

        const result = await response.json()
        console.log(result);

        return {
            success: true,
            result: result
        };

    } catch (err) {
        return {
            success: false,
            error: "Network error"
        };
    }
}

// Deposit
document.getElementById("depositForm")?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const data = Object.fromEntries(new FormData(form));

    const result = await postForm(window.seedbank.depositUrl, data);
    if (result?.error) {
        showMessage("error", result.error);
        return;
    } 

    const params = result.result.data
    showMessage("success", "Seeds deposited successfully!", params);
        
});

// Withdraw
document.getElementById("withdrawForm")?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const data = Object.fromEntries(new FormData(form));

    const result = await postForm(window.seedbank.withdrawUrl, data);
    if (result?.error) {
        showMessage("error", result.error);
        return;
    } 

    showMessage("success", "Seeds withdrawn successfully!");
        
});

