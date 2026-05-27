<?php
// Filename: pages/perjawatan.php
// Version: 8.4 - Fixes the indicator bar color logic for rounding issues at 95%.

// Data for Kumpulan Perkhidmatan
$byKumpulan = [
    'Anggota Pentadbiran' => ['nama_kumpulan' => 'Anggota Pentadbiran', 'perjawatan' => 4, 'pengisian' => 3],
    'Pengurusan Tertinggi' => ['nama_kumpulan' => 'Pengurusan Tertinggi', 'perjawatan' => 68, 'pengisian' => 64],
    'Pengurusan dan Profesional' => ['nama_kumpulan' => 'Pengurusan dan Profesional', 'perjawatan' => 1627, 'pengisian' => 1499],
    'Pegawai Perkhidmatan Pendidikan' => ['nama_kumpulan' => 'Pegawai Perkhidmatan Pendidikan', 'perjawatan' => 455150, 'pengisian' => 432319],
    'Anggota Kumpulan Pelaksana' => ['nama_kumpulan' => 'Anggota Kumpulan Pelaksana', 'perjawatan' => 85797, 'pengisian' => 77794],
    'Jawatan Terbuka AKP/P&P' => ['nama_kumpulan' => 'Jawatan Terbuka AKP/P&P', 'perjawatan' => 118, 'pengisian' => 68],
];

$keseluruhan = [
    'perjawatan' => 542764,
    'pengisian' => 511747,
];

// Data for Kategori Agensi
$byAgensi = [
    ['nama_agensi' => 'Kementerian', 'perjawatan' => 8695, 'pengisian' => 6741],
    ['nama_agensi' => 'JPN', 'perjawatan' => 8155, 'pengisian' => 7794],
    ['nama_agensi' => 'IAB', 'perjawatan' => 417, 'pengisian' => 346],
    ['nama_agensi' => 'Sekolah', 'perjawatan' => 503751, 'pengisian' => 478138],
    ['nama_agensi' => 'PPD', 'perjawatan' => 10219, 'pengisian' => 9721],
    ['nama_agensi' => 'IPGM', 'perjawatan' => 6390, 'pengisian' => 4733],
    ['nama_agensi' => 'DBP', 'perjawatan' => 801, 'pengisian' => 740],
    ['nama_agensi' => 'MPM', 'perjawatan' => 176, 'pengisian' => 145],
    ['nama_agensi' => 'YGTHO', 'perjawatan' => 20, 'pengisian' => 19],
    ['nama_agensi' => 'Kolej Matrikulasi', 'perjawatan' => 4140, 'pengisian' => 3370],
];


$data = [
    'data_sehingga' => '2025-09-05', // From image
    'keseluruhan' => $keseluruhan,
    'byKumpulan' => $byKumpulan,
    'byAgensi' => $byAgensi,
];

$frontendData = json_encode($data);
?>

<section id="page-perjawatan" class="bg-white p-4 sm:p-6 lg:p-8">
    <!-- Kontainer ini akan diisi oleh JavaScript secara dinamik -->
    <div id="dynamic-content-container"></div>
</section>

<script>
const backendData = <?php echo $frontendData; ?>;

// --- Helper Functions ---
const calculatePercentage = (value, total) => {
    // For display in the modal (string with %)
    if (total === 0) return '0.00%';
    const perc = (value / total) * 100;
    if (perc.toFixed(2).endsWith('.00')) { return perc.toFixed(0) + '%'; }
    return perc.toFixed(2) + '%';
};

const calculateFillPercentage = (pengisian, perjawatan) => {
    // For the progress bar width (number)
    if (perjawatan === 0) return 0;
    return (pengisian / perjawatan) * 100;
};

document.addEventListener('DOMContentLoaded', function () {
    buildPageContent(backendData);

    const dateSlicer = document.getElementById('date-slicer');
    if(dateSlicer) {
        dateSlicer.disabled = true;
        dateSlicer.style.cursor = 'not-allowed';
    }
    initializeTabsAndSearch();
    initializeModal();
});

function buildPageContent(data) {
    const container = document.getElementById('dynamic-content-container');
    if (!container) return;

    let html = `
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">Analisis Perjawatan & Pengisian</h1>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div class="relative">
                    <label class="text-xs sm:text-sm font-medium text-slate-600">Papar Data Sehingga</label>
                    <input type="date" id="date-slicer" value="${data.data_sehingga}" class="bg-slate-100 border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5 mt-1">
                </div>
                <div class="relative w-full sm:w-64">
                     <label class="text-xs sm:text-sm font-medium text-slate-600">Cari</label>
                    <input type="text" id="search-box" placeholder="Cari..." class="bg-slate-100 border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5 pl-10 mt-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none top-7">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend Updated Here -->
        <div class="flex justify-end items-center gap-4 text-xs text-slate-600 mb-4 pr-2">
            <span class="font-semibold">Petunjuk Bar Pengisian:</span>
            <div class="flex items-center gap-2">
                <div class="w-4 h-2 rounded-full bg-green-500"></div>
                <span>≥ 95%</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-2 rounded-full bg-blue-600"></div>
                <span>70% - 94%</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-2 rounded-full bg-red-500"></div>
                <span>< 70%</span>
            </div>
        </div>

        <div class="mb-4 border-b border-slate-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="perjawatanTabs" role="tablist">
                <li class="mr-2"><button class="inline-block p-4 border-b-2 rounded-t-lg" id="tab-kumpulan" type="button" role="tab" aria-controls="content-kumpulan">Mengikut Kumpulan Perkhidmatan</button></li>
                <li class="mr-2"><button class="inline-block p-4 border-b-2 rounded-t-lg" id="tab-agensi" type="button" role="tab" aria-controls="content-agensi">Mengikut Kategori Agensi</button></li>
            </ul>
        </div>
        <div id="perjawatanTabContent">
            ${ (data.byKumpulan && Object.keys(data.byKumpulan).length > 0) ? `
                <div id="content-kumpulan" role="tabpanel">${generateKumpulanContent(data)}</div>
                <div id="content-agensi" class="hidden" role="tabpanel">${generateAgensiContent(data)}</div>
            ` : `<div class="bg-amber-100 text-amber-800 p-4 rounded-md text-center">Tiada data ditemui.</div>` }
        </div>

        <!-- Universal Modal Structure -->
        <div id="detail-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center p-4 transition-opacity duration-300 z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl transform transition-transform duration-300 scale-95">
                <div class="p-4 border-b flex justify-between items-center bg-slate-50 rounded-t-xl">
                    <h3 id="modal-title" class="text-xl font-bold text-slate-800"></h3>
                    <button id="modal-close" class="text-3xl text-slate-500 hover:text-slate-900 font-bold">&times;</button>
                </div>
                <div id="modal-content" class="p-6">
                    <!-- Dynamic detailed content goes here -->
                </div>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

// --- Content Generation ---

const createCompactCard = (title, item, type, keyOrIndex) => {
    const perjawatan = parseInt(item.perjawatan || 0);
    const pengisian = parseInt(item.pengisian || 0);
    const kekosongan = perjawatan - pengisian;
    const keyAttribute = type === 'agensi' ? `data-index="${keyOrIndex}"` : `data-key="${keyOrIndex}"`;

    const peratusPengisian = calculateFillPercentage(pengisian, perjawatan);
    const roundedPeratus = Math.round(peratusPengisian); // Use rounded value for logic and display

    // Corrected Color Logic based on the rounded value
    let progressBarColor;
    if (roundedPeratus >= 95) {
        progressBarColor = 'bg-green-500';
    } else if (roundedPeratus < 70) {
        progressBarColor = 'bg-red-500';
    } else {
        progressBarColor = 'bg-blue-600';
    }

    return `
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:border-indigo-400 hover:shadow-md cursor-pointer transition-all duration-200 card modal-trigger flex flex-col justify-between"
             data-title="${title}"
             data-type="${type}"
             ${keyAttribute}>
            <div>
                <h4 class="font-bold text-slate-800 text-center text-base mb-3 truncate">${title}</h4>
                <div class="flex justify-around text-center border-t pt-3">
                    <div>
                        <p class="font-semibold text-lg text-blue-700">${perjawatan.toLocaleString()}</p>
                        <p class="text-xs text-slate-500">Perjawatan</p>
                    </div>
                    <div>
                        <p class="font-semibold text-lg text-green-700">${pengisian.toLocaleString()}</p>
                        <p class="text-xs text-slate-500">Pengisian</p>
                    </div>
                    <div>
                        <p class="font-semibold text-lg text-red-700">${kekosongan.toLocaleString()}</p>
                        <p class="text-xs text-slate-500">Kekosongan</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="${progressBarColor} h-2 rounded-full" style="width: ${peratusPengisian.toFixed(0)}%"></div>
                </div>
                <p class="text-right text-xs text-slate-500 mt-1 font-semibold">${roundedPeratus}%</p>
            </div>
        </div>
    `;
};

function generateKumpulanContent(data) {
    if (!data.byKumpulan) {
        return `<div class="text-center p-6 bg-slate-100 rounded-lg">Tiada data kumpulan ditemui.</div>`;
    }

    let cardsHtml = createCompactCard('Keseluruhan', data.keseluruhan, 'kumpulan', 'keseluruhan');
    const kumpulanOrder = [
        'Anggota Pentadbiran', 'Pengurusan Tertinggi', 'Pengurusan dan Profesional',
        'Pegawai Perkhidmatan Pendidikan', 'Anggota Kumpulan Pelaksana', 'Jawatan Terbuka AKP/P&P'
    ];
    kumpulanOrder.forEach(key => {
        if (data.byKumpulan[key]) {
            cardsHtml += createCompactCard(key, data.byKumpulan[key], 'kumpulan', key);
        }
    });

    return `<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 searchable-cards">${cardsHtml}</div>`;
}

function generateAgensiContent(data) {
    if (!data.byAgensi || data.byAgensi.length === 0) {
        return `<div class="text-center p-6 bg-slate-100 rounded-lg">Tiada data agensi ditemui.</div>`;
    }
    let cardsHtml = (data.byAgensi || []).map((item, index) => {
        return createCompactCard(item.nama_agensi, item, 'agensi', index);
    }).join('');

    return `<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 searchable-cards">${cardsHtml}</div>`;
}


// --- Modal and Interactivity ---

function generateDetailedViewForModal(data) {
    const createStatCard = (title, value, percentage, colorTheme) => {
        const colors = {
            blue: { bg: 'bg-blue-50', text: 'text-blue-800', value: 'text-blue-900', border: 'border-blue-200' },
            green: { bg: 'bg-green-50', text: 'text-green-800', value: 'text-green-900', border: 'border-green-200' },
            red: { bg: 'bg-red-50', text: 'text-red-800', value: 'text-red-900', border: 'border-red-200' }
        };
        const theme = colors[colorTheme];

        return `
            <div class="${theme.bg} p-4 rounded-lg text-center flex flex-col justify-center border ${theme.border}">
                <p class="text-base font-semibold ${theme.text}">${title}</p>
                <p class="text-5xl font-bold ${theme.value} my-1">${value.toLocaleString()}</p>
                ${percentage ? `<p class="text-base font-semibold ${theme.text}">Peratus: ${percentage}</p>` : '<p class="text-base">&nbsp;</p>'}
            </div>
        `;
    };

    const perjawatan = parseInt(data.perjawatan || 0);
    const pengisian = parseInt(data.pengisian || 0);
    const kekosongan = perjawatan - pengisian;

    return `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            ${createStatCard('Perjawatan', perjawatan, null, 'blue')}
            ${createStatCard('Pengisian', pengisian, calculatePercentage(pengisian, perjawatan), 'green')}
            ${createStatCard('Kekosongan', kekosongan, calculatePercentage(kekosongan, perjawatan), 'red')}
        </div>
    `;
}

function initializeModal() {
    const modal = document.getElementById('detail-modal');
    const modalContainer = modal.querySelector('div');
    const modalClose = document.getElementById('modal-close');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');
    
    if (!modal || !modalClose) return;

    const openModal = (trigger) => {
        const title = trigger.dataset.title;
        const type = trigger.dataset.type;
        let dataObj = null;

        if (type === 'kumpulan') {
            const key = trigger.dataset.key;
            dataObj = (key === 'keseluruhan') ? backendData.keseluruhan : backendData.byKumpulan[key];
        } else if (type === 'agensi') {
            const index = trigger.dataset.index;
            dataObj = backendData.byAgensi[index];
        }

        if (!dataObj) return;

        modalTitle.innerText = title;
        modalContent.innerHTML = generateDetailedViewForModal(dataObj);
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => modal.classList.add('opacity-100'), 10);
        setTimeout(() => modalContainer.classList.remove('scale-95'), 50);
    };

    const closeModal = () => {
        modalContainer.classList.add('scale-95');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    };

    document.body.addEventListener('click', function(e) {
        const trigger = e.target.closest('.modal-trigger');
        if (trigger) {
            openModal(trigger);
        }
    });

    modalClose.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => (e.target === modal) && closeModal());
}

function initializeTabsAndSearch(){
    const tabs = document.querySelectorAll('[role="tab"]');
    const tabContents = document.querySelectorAll('[role="tabpanel"]');
    const searchBox = document.getElementById('search-box');

    if (tabs.length > 0 && tabContents.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => {
                    t.setAttribute('aria-selected', 'false');
                    t.classList.remove('border-indigo-500', 'text-indigo-600');
                    t.classList.add('border-transparent', 'hover:text-slate-600', 'hover:border-slate-300');
                });
                tabContents.forEach(c => c.classList.add('hidden'));

                tab.setAttribute('aria-selected', 'true');
                tab.classList.remove('border-transparent', 'hover:text-slate-600', 'hover:border-slate-300');
                tab.classList.add('border-indigo-500', 'text-indigo-600');
                const contentToShow = document.getElementById(tab.getAttribute('aria-controls'));
                if(contentToShow) contentToShow.classList.remove('hidden');
                
                const placeholder = tab.id === 'tab-agensi' ? 'Cari Agensi...' : 'Cari Kumpulan...';
                searchBox.placeholder = placeholder;
                searchBox.value = '';
                filterCards('');
            });
        });
        
        const firstTab = document.getElementById('tab-kumpulan');
        if (firstTab) firstTab.click();
    }

    if(searchBox) searchBox.addEventListener('keyup', (e) => filterCards(e.target.value.toLowerCase()));
}

function filterCards(searchTerm) { 
    const activeTabContent = document.querySelector('[role="tabpanel"]:not(.hidden)');
    if (!activeTabContent) return;
    const cards = activeTabContent.querySelectorAll('.card');
    cards.forEach(card => {
        const title = (card.dataset.title || '').toLowerCase();
        card.style.display = title.includes(searchTerm) ? '' : 'none';
    });
}
</script>

