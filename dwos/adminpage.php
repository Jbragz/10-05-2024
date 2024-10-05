<?php
include('dwos.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch top 3 subscribers based on duration_in_days
$topSubscribersSql = "SELECT u.user_name, m.duration_in_days 
                      FROM subscriptions s 
                      JOIN users u ON s.owner_id = u.user_id 
                      JOIN memberships m ON s.membership_id = m.membership_id 
                      ORDER BY m.duration_in_days DESC 
                      LIMIT 3"; // Limit to top 3
$topSubscribersResult = $conn->query($topSubscribersSql);

// Fetch all subscribers for modal
$allSubscribersSql = "SELECT u.user_name, m.duration_in_days 
                      FROM subscriptions s 
                      JOIN users u ON s.owner_id = u.user_id 
                      JOIN memberships m ON s.membership_id = m.membership_id 
                      ORDER BY m.duration_in_days DESC"; 
$allSubscribersResult = $conn->query($allSubscribersSql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminpage.css" />
    <title>Admin Page</title>
</head>
<body>
    <?php include 'adminnavbar.php'; ?>

    <div class="home-container">
        <!-- Top Selling Section -->
        <section class="top-selling">
            <h2>TOP SELLING</h2>
            <ul class="list">
                <li class="home"><span class="home-id">1.</span>Water</li>
                <li class="home"><span class="home-id">2.</span>2beg</li>
                <li class="home"><span class="home-id">3.</span>Lee REFILLING STATION</li>
                <li class="home hidden"><span class="home-id">4.</span>Waturrrr</li>
                <li class="home hidden"><span class="home-id">5.</span>Bongga</li>
                <li class="home hidden"><span class="home-id">6.</span>Inomi na</li>
            </ul>
            <div class="show-all">
                <button class="btn" data-modal="top-selling-modal">Show All</button>
            </div>
        </section>


    <div class="home-container">
        <!-- Top Subscribers Section -->
        <section class="top-subscriber">
            <h2>TOP SUBSCRIBERS</h2>
            <ul class="list">
                <?php
                // Display the top 3 subscribers
                if ($topSubscribersResult->num_rows > 0) {
                    $rank = 1;
                    while ($row = $topSubscribersResult->fetch_assoc()) {
                        echo "<li class='home'><span class='home-id'>{$rank}.</span>{$row['user_name']}</li>";
                        $rank++;
                    }
                } else {
                    echo "<li class='home'>No subscribers found.</li>";
                }
                ?>
            </ul>
            <div class="show-all">
                <button class="btn" data-modal="top-subscriber-modal">Show All</button>
            </div>
        </section>
    </div>

    <!-- Modal for Top Subscribers -->
    <div id="top-subscriber-modal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-close="top-subscriber-modal">&times;</span>
            <h2>ALL TOP SUBSCRIBERS</h2>
            <ul class="full-list">
                <?php
                // Display all subscribers in the modal
                if ($allSubscribersResult->num_rows > 0) {
                    $rank = 1;
                    while ($row = $allSubscribersResult->fetch_assoc()) {
                        echo "<li><span class='home-id'>{$rank}.</span>{$row['user_name']} -{$row['duration_in_days']} days</li>";
                        $rank++;
                    }
                } else {
                    echo "<li>No subscribers found.</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <script>
    // Function to open modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex'; // Show modal
        }
    }

    // Function to close modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none'; // Hide modal
        }
    }

    // Attach event listeners to "Show All" buttons
    document.querySelectorAll('.show-all .btn').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal');
            openModal(modalId);
        });
    });

    // Attach event listeners to close buttons
    document.querySelectorAll('.close-button').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-close');
            closeModal(modalId);
        });
    });

    // Close modal when clicking outside the modal content
    window.addEventListener('click', (event) => {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    });
    </script>
</body>
</html>
