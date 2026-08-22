@extends('layouts.app')


@section('title', 'My Account - Tradim')


@section('content')


    <div class="home-container">


        <div class="row justify-content-center">

            <div class="col-xl-9">


                <div class="hero-banner" style="min-height:260px;">

                    <div class="hero-content">


                        <span class="hero-badge">

                            <i class="bi bi-person-check"></i>

                            TRADIM ACCOUNT

                        </span>


                        <h1 class="hero-title" style="font-size:42px;">

                            Welcome,

                            <span class="gradient-text">

                                {{ Auth::user()->name }}

                            </span>

                        </h1>


                        <p class="hero-description">

                            @if(Auth::user()->bio)

                                {{ Auth::user()->bio }}

                            @else

                                Welcome to your Tradim account.

                                Your creator journey starts here.

                            @endif

                        </p>


                        <div class="hero-buttons">


                            <a href="#" class="btn-tradim">

                                <i class="bi bi-person"></i>

                                Edit Profile

                            </a>


                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">

                                @csrf

                                <button type="submit" class="btn-tradim-outline">

                                    <i class="bi bi-box-arrow-right"></i>

                                    Logout

                                </button>

                            </form>


                        </div>

                    </div>


                </div>



                <div class="row g-4 mt-1">


                    <div class="col-md-4">

                        <div class="feature-strip">

                            <div class="feature-item">

                                <div class="feature-icon">

                                    <i class="bi bi-play-btn"></i>

                                </div>

                                <div>

                                    <h6>
                                        Videos
                                    </h6>

                                    <p>
                                        0 uploaded
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="feature-strip">

                            <div class="feature-item">

                                <div class="feature-icon">

                                    <i class="bi bi-people"></i>

                                </div>

                                <div>

                                    <h6>
                                        Subscribers
                                    </h6>

                                    <p>
                                        0 subscribers
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="feature-strip">

                            <div class="feature-item">

                                <div class="feature-icon">

                                    <i class="bi bi-eye"></i>

                                </div>

                                <div>

                                    <h6>
                                        Views
                                    </h6>

                                    <p>
                                        0 total views
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                </div>


            </div>

        </div>


    </div>


@endsection