@extends('layouts.app')

@section('title','Dashboard')

@push('styles')
<link rel="stylesheet" href="css/chart.min.css">
<style>
    body {
        background-color: #f8fafc;
        padding: 20px;
    }

    .dashboard-card {
        background: #fff;
        border: none;
        border-radius: 5px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: all 0.3s ease;
        height: 150px;
        justify-content: start;
        align-items: center;
        padding: 16px 24px;
        display: flex;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .dashboard-icon {
        width: 48px;
        height: 48px;
        margin-right: 24px;
        object-fit: contain;
    }

    .dashboard-value {
        font-weight: 600;
        text-align: start;
    }

    /* Colors */
    .text-teal {
        color: #03A6A1;
        font-size: 24px;
    }

    .text-pink {
        color: #EA2264;
        font-size: 24px;
    }

    .text-green {
        color: #59AC77;
        font-size: 24px;
    }

    .text-purple {
        color: #B5179E;
        font-size: 24px;
    }

    .text-orange {
        color: #FF714B;
        font-size: 24px;
    }

    .text-blue {
        color: #007BFF;
        font-size: 24px;
    }

    .text-cyan {
        color: #1E93AB;
        font-size: 24px;
    }

    .text-green-secondary {
        color: #67C090;
        font-size: 24px;
    }

    .text-purple-secondary {
        color: #9929EA;
        font-size: 24px;
    }

    .text-ash-blue {
        color: #696FC7;
        font-size: 24px;
    }

    .text-blue-secondary {
        color: #0BA6DF;
        font-size: 24px;
    }

    .card-text {
        font-size: 16px;
        text-align: start;
        color: #000;
    }

    /* Grid layout fix */
    .dashboard-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        /* ✅ Aligns last row to the left */
        gap: 20px;
    }

    .dashboard-row .dashboard-col {
        flex: 1 1 calc(33.333% - 20px);
        max-width: calc(33.333% - 20px);
    }

    @media (max-width: 992px) {
        .dashboard-row .dashboard-col {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(50% - 20px);
        }
    }

    @media (max-width: 576px) {
        .dashboard-row .dashboard-col {
            flex: 1 1 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="dashboard-row">

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/CurrencyDollar.svg') }}" class="dashboard-icon"
                        alt="Collection">
                    <div class="dashboard-value text-teal">{{ $collection ?? 0 }} TK
                        <p class="card-text"><small>Collection</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/HandCoins.svg') }}" class="dashboard-icon" alt="Due">
                    <div class="dashboard-value text-pink">{{ $due ?? 0 }} TK
                        <p class="card-text"><small>Due</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/Files.svg') }}" class="dashboard-icon"
                        alt="Application Received">
                    <div class="dashboard-value text-green">{{ $received ?? 0 }}
                        <p class="card-text"><small>Application Received</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/inprocess.svg') }}" class="dashboard-icon" alt="In Process">
                    <div class="dashboard-value text-purple">{{ $processing ?? 0 }}
                        <p class="card-text"><small>Application in Process</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/missing.svg') }}" class="dashboard-icon" alt="Required Missing">
                    <div class="dashboard-value text-orange">{{ $missing_Documents ?? 0}}
                        <p class="card-text"><small>Required Missing</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/embassy.svg') }}" class="dashboard-icon" alt="Submit Embassy">
                    <div class="dashboard-value text-cyan">{{ $submitted_to_embassy ?? 0}}
                        <p class="card-text"><small>Submit Embassy</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/IdentificationCard.svg') }}" class="dashboard-icon"
                        alt="Ready for Delivery">
                    <div class="dashboard-value text-blue">{{ $ready_for_delivery ?? 0}}
                        <p class="card-text"><small>Ready for Delivery</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/SealCheck.svg') }}" class="dashboard-icon" alt="Delivered">
                    <div class="dashboard-value text-green-secondary">{{ $delivered ?? 0}}
                        <p class="card-text"><small>Delivered</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/IdentificationBadge.svg') }}" class="dashboard-icon"
                        alt="Visa Application">
                    <div class="dashboard-value text-purple-secondary">{{ $application ?? 0 }}
                        <p class="card-text"><small>Visa Application</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/Users.svg') }}" class="dashboard-icon" alt="Customer">
                    <div class="dashboard-value text-ash-blue">{{ $customer ?? 0 }}
                        <p class="card-text"><small>Customer</small></p>
                    </div>
                </div>
            </div>

            <div class="dashboard-col">
                <div class="dashboard-card">
                    <img src="{{ asset('dashboard/icons/UsersThree.svg') }}" class="dashboard-icon" alt="Employee">
                    <div class="dashboard-value text-blue-secondary">{{ $employee ?? 0 }}
                        <p class="card-text"><small>Employee</small></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection