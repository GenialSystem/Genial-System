import "./bootstrap";
import { Chart } from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";
import { Calendar } from "@fullcalendar/core";
import DayGridPlugin from "@fullcalendar/daygrid";

window.Calendar = Calendar;
window.DayGridPlugin = DayGridPlugin;
window.Chart = Chart;
window.ChartDataLabels = ChartDataLabels;
let presenceChannel = null;
let isChannelJoined = false; // Flag to check if the channel is already joined
// Object to keep track of online users
window.onlineUsers = {};

document.addEventListener("DOMContentLoaded", function () {
    if (!isChannelJoined) {
        presenceChannel = window.Echo.join("online-users")
            .here((users) => {
                console.log(users);
                // console.log("Users currently online:", users);
                users.forEach((user) => {
                    onlineUsers[user.id] = true; // Mark user as online in the object
                    updateUserStatus(user.id, true); // Update UI to reflect online status
                });
            })
            .joining((user) => {
                // console.log(user.name + " joined the channel.");
                onlineUsers[user.id] = true; // Mark user as online in the object
                // console.log(user.id);
                updateUserStatus(user.id, true); // Update UI to reflect online status
                console.log(user);
            })
            .leaving((user) => {
                // console.log(user.name + " left the channel.");
                delete onlineUsers[user.id]; // Remove user from the online users object
                updateUserStatus(user.id, false); // Update UI to reflect offline status
                console.log(user);
            })
            .error((error) => {
                console.error("Failed to join the channel:", error);
            })
            .listen("UserOnlineEvent", (e) => {
                console.log(e);
            });

        isChannelJoined = true; // Set the flag to true to prevent rejoining
    }
});

// Function to update user online status
function updateUserStatus(userId, isOnline) {
    const checkInterval = setInterval(() => {
        const statusElement = document.getElementById(`status-${userId}`);
        const statusTextElement = document.getElementById(
            `status-text-${userId}`
        );
        if (statusElement) {
            statusElement.className = `w-2 h-2 rounded-full mr-2 ${
                isOnline ? "bg-green-500" : "bg-red-500"
            }`;
            if (statusTextElement) {
                statusTextElement.innerText = isOnline ? "Online" : "Offline";
            }
            clearInterval(checkInterval); // Interrompi il loop
        }
    }, 100); // Controlla ogni 100ms
}

// Ensure the channel is left when the user closes the tab or navigates away
window.addEventListener("beforeunload", function () {
    if (presenceChannel && typeof presenceChannel.leave === "function") {
        presenceChannel.leave();
        isChannelJoined = false; // Reset the flag when leaving
    } else {
        console.warn("No valid presence channel to leave.");
    }
});
