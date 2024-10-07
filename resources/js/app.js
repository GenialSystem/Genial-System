import "./bootstrap";
import { Chart } from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";
import { Calendar } from "@fullcalendar/core";
import DayGridPlugin from "@fullcalendar/daygrid";

window.Calendar = Calendar;
window.DayGridPlugin = DayGridPlugin;
window.Chart = Chart;
window.ChartDataLabels = ChartDataLabels;
