<?php
$page_title = "Analytics & Reports";
$page_subtitle = "Clean insight into aid request performance";
require '../app/views/partials/counselor_header.php';

$summary = $summary ?? [
  'total_requests' => 0,
  'approval_rate' => 0,
  'total_disbursed' => 0,
  'avg_processing_days' => 0,
];

$timeline = is_array($timeline ?? null) ? $timeline : [];
$statusMix = $statusMix ?? [
  'pending' => 0,
  'approved' => 0,
  'completed' => 0,
  'rejected' => 0,
];

$days = max(7, (int)($days ?? 30));

$labels = [];
$totalSeries = [];
$approvedSeries = [];
$rejectedSeries = [];
$disbursedSeries = [];

for ($i = $days - 1; $i >= 0; $i--) {
  $dateKey = date('Y-m-d', strtotime('-' . $i . ' days'));
  $labels[] = date('M j', strtotime($dateKey));

  $point = $timeline[$dateKey] ?? null;
  $totalSeries[] = (int)($point['total'] ?? 0);
  $approvedSeries[] = (int)($point['approved'] ?? 0);
  $rejectedSeries[] = (int)($point['rejected'] ?? 0);
  $disbursedSeries[] = (float)($point['disbursed'] ?? 0);
}

$processedCount = (int)($statusMix['approved'] ?? 0) + (int)($statusMix['completed'] ?? 0) + (int)($statusMix['rejected'] ?? 0);
$approvalRateForProcessed = $processedCount > 0
  ? round((((int)($statusMix['approved'] ?? 0) + (int)($statusMix['completed'] ?? 0)) / $processedCount) * 100, 1)
  : 0;
?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/counselor.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section analytics-shell">
      <div class="analytics-hero">
        <h2 class="hero-title">Aid Analytics Overview</h2>
        <p class="hero-subtitle">Last <?= (int)$days ?> days performance snapshot with trends and outcomes.</p>
      </div>

      <div class="kpi-grid">
        <article class="kpi-card kpi-delay-1">
          <p class="kpi-label">Total Requests</p>
          <p class="kpi-value"><?= (int)($summary['total_requests'] ?? 0) ?></p>
          <p class="kpi-note">Total aid submissions recorded.</p>
        </article>

        <article class="kpi-card kpi-delay-2">
          <p class="kpi-label">Approval Rate</p>
          <p class="kpi-value"><?= esc((string)($summary['approval_rate'] ?? 0)) ?>%</p>
          <p class="kpi-note">Share of approved among processed requests.</p>
        </article>

        <article class="kpi-card kpi-delay-3">
          <p class="kpi-label">Avg. Processing Days</p>
          <p class="kpi-value"><?= esc((string)($summary['avg_processing_days'] ?? 0)) ?></p>
          <p class="kpi-note">Average days from submission to decision.</p>
        </article>

        <article class="kpi-card kpi-delay-4">
          <p class="kpi-label">Total Money Disbursed</p>
          <p class="kpi-value">LKR <?= number_format((float)($summary['total_disbursed'] ?? 0), 0) ?></p>
          <p class="kpi-note">Approved and completed disbursement total.</p>
        </article>
      </div>

      <div class="chart-grid">
        <article class="chart-card">
          <h3 class="chart-title">Support Demand Trend</h3>
          <p class="chart-subtitle">How aid request volume has moved day by day.</p>
          <div class="chart-box">
            <canvas id="demandTrendChart"></canvas>
          </div>
        </article>

        <article class="chart-card">
          <h3 class="chart-title">Outcome Mix</h3>
          <p class="chart-subtitle">Current distribution by request outcome.</p>
          <div class="chart-box">
            <canvas id="outcomeMixChart"></canvas>
          </div>
        </article>
      </div>

      <div class="chart-grid chart-grid-single">
        <article class="chart-card chart-delay-2">
          <h3 class="chart-title">Daily Disbursement Trend</h3>
          <p class="chart-subtitle">Amount committed across approved/completed requests over time.</p>
          <div class="chart-box chart-box-compact">
            <canvas id="disbursementTrendChart"></canvas>
          </div>
        </article>
      </div>

      <div class="insight-strip">
        <div>
          <p class="insight-title">Operational Signal</p>
          <p class="insight-text">Processed-request success rate (approved + completed) is <strong><?= number_format($approvalRateForProcessed, 1) ?>%</strong> across the selected window.</p>
        </div>
        <span class="insight-pill"><?= (int)$processedCount ?> Processed</span>
      </div>
    </section>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
  (function () {
    const labels = <?= json_encode($labels, JSON_UNESCAPED_SLASHES) ?>;
    const totalSeries = <?= json_encode($totalSeries, JSON_UNESCAPED_SLASHES) ?>;
    const approvedSeries = <?= json_encode($approvedSeries, JSON_UNESCAPED_SLASHES) ?>;
    const rejectedSeries = <?= json_encode($rejectedSeries, JSON_UNESCAPED_SLASHES) ?>;
    const disbursedSeries = <?= json_encode($disbursedSeries, JSON_UNESCAPED_SLASHES) ?>;

    const approvedSeriesSparse = approvedSeries.map((v) => (Number(v) > 0 ? Number(v) : null));
    const rejectedSeriesSparse = rejectedSeries.map((v) => (Number(v) > 0 ? Number(v) : null));

    const statusMix = {
      pending: <?= (int)($statusMix['pending'] ?? 0) ?>,
      approved: <?= (int)($statusMix['approved'] ?? 0) ?>,
      completed: <?= (int)($statusMix['completed'] ?? 0) ?>,
      rejected: <?= (int)($statusMix['rejected'] ?? 0) ?>
    };

    const blueDark = '#1e3a8a';
    const blueBase = '#2563eb';
    const blueMid = '#3b82f6';
    const blueSoft = '#93c5fd';
    const bluePale = '#dbeafe';

    const maxDemand = Math.max(
      ...totalSeries.map((v) => Number(v) || 0),
      ...approvedSeries.map((v) => Number(v) || 0),
      ...rejectedSeries.map((v) => Number(v) || 0),
      1
    );

    const baseOptions = {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false
      },
      animation: {
        duration: 900,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: {
          labels: {
            boxWidth: 10,
            usePointStyle: true,
            pointStyle: 'circle',
            padding: 14,
            color: '#334155',
            font: {
              family: 'Poppins'
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(15, 23, 42, 0.92)',
          titleColor: '#ffffff',
          bodyColor: '#e2e8f0',
          padding: 10,
          callbacks: {
            label: function (context) {
              const value = Number(context.parsed.y || 0);
              return context.dataset.label + ': ' + value;
            }
          }
        }
      },
      scales: {
        x: {
          ticks: {
            color: '#64748b',
            autoSkip: true,
            maxTicksLimit: 8,
            maxRotation: 35,
            minRotation: 0
          },
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: '#64748b',
            precision: 0,
            stepSize: 1,
            maxTicksLimit: 6
          },
          grid: { color: 'rgba(148, 163, 184, 0.16)' }
        }
      }
    };

    const demandCtx = document.getElementById('demandTrendChart');
    if (demandCtx) {
      new Chart(demandCtx, {
        type: 'line',
        data: {
          labels,
          datasets: [
            {
              label: 'Total Requests',
              data: totalSeries,
              borderColor: blueBase,
              backgroundColor: 'rgba(147, 197, 253, 0.34)',
              borderWidth: 2.6,
              tension: 0.25,
              fill: true,
              pointRadius: 2,
              pointHoverRadius: 4
            },
            {
              label: 'Approved',
              data: approvedSeriesSparse,
              borderColor: blueDark,
              backgroundColor: blueDark,
              tension: 0.32,
              spanGaps: true,
              fill: false,
              pointRadius: 2,
              pointHoverRadius: 4,
              borderWidth: 2.6
            },
            {
              label: 'Rejected',
              data: rejectedSeriesSparse,
              borderColor: blueSoft,
              backgroundColor: blueSoft,
              tension: 0.32,
              spanGaps: true,
              fill: false,
              pointRadius: 2,
              pointHoverRadius: 4,
              borderWidth: 2.4
            }
          ]
        },
        options: {
          ...baseOptions,
          scales: {
            ...baseOptions.scales,
            y: {
              ...baseOptions.scales.y,
              suggestedMax: maxDemand + 1,
              ticks: {
                ...baseOptions.scales.y.ticks,
                stepSize: 1
              }
            }
          }
        }
      });
    }

    const mixCtx = document.getElementById('outcomeMixChart');
    if (mixCtx) {
      new Chart(mixCtx, {
        type: 'doughnut',
        data: {
          labels: ['Pending', 'Approved', 'Completed', 'Rejected'],
          datasets: [{
            data: [statusMix.pending, statusMix.approved, statusMix.completed, statusMix.rejected],
            backgroundColor: [bluePale, blueSoft, blueBase, blueDark],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '62%',
          animation: {
            duration: 900,
            easing: 'easeOutQuart'
          },
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                boxWidth: 10,
                color: '#334155',
                font: { family: 'Poppins' }
              }
            }
          }
        }
      });
    }

    const disbursedCtx = document.getElementById('disbursementTrendChart');
    if (disbursedCtx) {
      new Chart(disbursedCtx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'LKR Disbursed',
            data: disbursedSeries,
            backgroundColor: 'rgba(37, 99, 235, 0.78)',
            borderColor: blueDark,
            borderWidth: 1,
            borderRadius: 7,
            maxBarThickness: 26
          }]
        },
        options: {
          ...baseOptions,
          plugins: {
            ...baseOptions.plugins,
            legend: { display: false }
          },
          scales: {
            ...baseOptions.scales,
            y: {
              ...baseOptions.scales.y,
              ticks: {
                color: '#64748b',
                callback: (value) => 'LKR ' + Number(value).toLocaleString()
              }
            }
          }
        }
      });
    }
  })();
</script>

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
