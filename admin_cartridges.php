<?php
session_start();
include 'dbcon.php';
include 'includes/header.php'; 
echo ' | Manage Cartridge Submissions';
include 'includes/header2.php';
include 'includes/navbar_admin.php';

if (!isset($_SESSION['verified_user_id']) || !isset($_SESSION['idTokenString'])) {
    $_SESSION['status'] = "Not Allowed.";
    header("Location: login.php");
    exit();
}

$submissionsRef = $database->getReference('cartridge_submissions');
$submissions = $submissionsRef->getValue();

$usersRef = $database->getReference('users');
$users = $usersRef->getValue();

// Separate submissions into pending and completed
$pendingSubmissions = [];
$completedSubmissions = [];

if ($submissions) {
    foreach ($submissions as $userID => $userSubmissions) {
        if (is_array($userSubmissions)) {
            foreach ($userSubmissions as $submissionID => $submission) {
                if (isset($submission['status']) && $submission['status'] === 'Completed') {
                    $completedSubmissions[$userID][$submissionID] = $submission;
                } else {
                    $pendingSubmissions[$userID][$submissionID] = $submission;
                }
            }
        }
    }
}

// Display Pending Submissions
echo "<h2>Pending Cartridge Submissions</h2>";
if ($pendingSubmissions) {
    foreach ($pendingSubmissions as $userID => $userSubmissions) {
        foreach ($userSubmissions as $submissionID => $submission) {
            echo "<div class='submission'>";
            
            $userName = isset($users[$userID]['name']) ? htmlspecialchars($users[$userID]['name']) : 'N/A';
            echo "User Name: " . $userName . "<br>";
            
            echo "User ID: " . htmlspecialchars($submission['user_id'] ?? 'N/A') . "<br>";
            echo "Brand: " . htmlspecialchars($submission['brand'] ?? 'N/A') . "<br>";
            echo "Model: " . htmlspecialchars($submission['model'] ?? 'N/A') . "<br>";
            echo "Quantity: " . htmlspecialchars($submission['quantity'] ?? 'N/A') . "<br>";
            
            // Button to open modal
            echo "<button onclick=\"openModal('$userID', '$submissionID')\">Expand</button>";
            echo "<hr>";
            echo "</div>";
        }
    }
} else {
    echo "<p>No pending submissions found.</p>";
}

// Display Completed Submissions
echo "<h2>Completed Cartridge Submissions</h2>";
if ($completedSubmissions) {
    foreach ($completedSubmissions as $userID => $userSubmissions) {
        foreach ($userSubmissions as $submissionID => $submission) {
            echo "<div class='submission completed'>";
            
            $userName = isset($users[$userID]['name']) ? htmlspecialchars($users[$userID]['name']) : 'N/A';
            echo "User Name: " . $userName . "<br>";
            
            echo "User ID: " . htmlspecialchars($submission['user_id'] ?? 'N/A') . "<br>";
            echo "Brand: " . htmlspecialchars($submission['brand'] ?? 'N/A') . "<br>";
            echo "Model: " . htmlspecialchars($submission['model'] ?? 'N/A') . "<br>";
            echo "Quantity: " . htmlspecialchars($submission['quantity'] ?? 'N/A') . "<br>";
            echo "Status: Completed<br>";
            
            echo "<hr>";
            echo "</div>";
        }
    }
} else {
    echo "<p>No completed submissions found.</p>";
}
?>

<!-- Modal Structure -->
<div id="submissionModal" class="modal modalac" style="display:none;">
    <div class="modal-content modal-contentac">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Submission Details</h2>
        <p id="modalContent">Loading...</p>
        
        <!-- Payment Simulation Button -->
        <button id="simulatePaymentButton">Pay User</button>
        
        <!-- Mark Complete Button -->
        <button id="markCompleteButton" disabled>Mark as Complete</button>
    </div>
</div>
<br>
<?php
include 'includes\footer.php';
?>

<script>
function openModal(userID, submissionID) {
    const modal = document.getElementById("submissionModal");
    const modalContent = document.getElementById("modalContent");
    const markCompleteButton = document.getElementById("markCompleteButton");
    const simulatePaymentButton = document.getElementById("simulatePaymentButton");
    
    modalContent.innerHTML = "Loading...";
    modal.style.display = "block";
    
    fetch(`get_submission_details.php?userID=${userID}&submissionID=${submissionID}`)
        .then(response => response.json())
        .then(data => {
            modalContent.innerHTML = `
                <p>User ID: ${data.user_id}</p>
                <p>Brand: ${data.brand}</p>
                <p>Model: ${data.model}</p>
                <p>Quantity: ${data.quantity}</p>
                <p>Payment Method: ${data.payment_method}</p>
                <p>Payment Details: ${data.payment_details}</p>
                <p>Status: ${data.status}</p>
                <img src="${data.image_url}" alt="Cartridge Image" style="max-width: 100%; height: auto;">
            `;

            simulatePaymentButton.onclick = function() {
                simulatePayment(userID, submissionID);
            };
            
            markCompleteButton.disabled = data.status !== 'Payment Simulated';
            markCompleteButton.onclick = function() {
                markAsComplete(userID, submissionID);
            };
        });
}

function simulatePayment(userID, submissionID) {
    fetch(`cartridgepayment.php?userID=${userID}&submissionID=${submissionID}`, { method: 'POST' })
        .then(response => response.text())
        .then(message => {
            alert(message);
            document.getElementById("markCompleteButton").disabled = false;
            openModal(userID, submissionID);
        });
}

function markAsComplete(userID, submissionID) {
    fetch(`mark_as_complete.php?userID=${userID}&submissionID=${submissionID}`, { method: 'POST' })
        .then(response => response.text())
        .then(message => {
            alert(message);
            closeModal();
            location.reload();
        });
}

function closeModal() {
    document.getElementById("submissionModal").style.display = "none";
}
</script>
