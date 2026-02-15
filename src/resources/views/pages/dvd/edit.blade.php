@extends('layouts.base')

@section('title', $dvd->title)

@section('content')
    <h1>{{ $dvd->title }}</h1>

    <form action="" method="POST">
        @csrf
        <label>Season</label>
        <input type="number" id="season" name="season" value="{{$dvd->season}}"/>
        <input type="hidden" id="disc_type" name="disc_type" value="{{ $dvd->disc_type }}"/>

        <div role="group">
            <button type="button" id="disc_type_dvd">DvD</button>
            <button type="button" id="disc_type_blueray" class="secondary">Blueray</button>
        </div>

        <input type="submit" class="w-100" value="Save"/>
    </form>

    <a id="delete" class="red w-100" role="button">Delete</a>

    <dialog id="delete-modal">
        <article>
            <header>
            <button id="close" aria-label="Close" rel="prev"></button>
            <p>
                <strong>Delete?</strong>
            </p>
            </header>
            <p>Delete this DvD?</p>
            <a href="/dvd/{{ $dvd->id }}/delete" class="red" role="button">Delete</a>
            <a id="no" class="secondary" role="button">No</a>
        </article>
    </dialog>


    <script>
(() => {

    const updateButtonGroup = () => {
        if(disc_type.value == "dvd") {
            document.getElementById('disc_type_dvd').classList.remove("secondary");
            document.getElementById('disc_type_blueray').classList.add("secondary");
        } else {
            document.getElementById('disc_type_blueray').classList.remove("secondary");
            document.getElementById('disc_type_dvd').classList.add("secondary");
        }
    };
    document.getElementById('disc_type_dvd').addEventListener('click',() => {disc_type.value = "dvd"; updateButtonGroup();});
    document.getElementById('disc_type_blueray').addEventListener('click',() => {disc_type.value = "blueray"; updateButtonGroup();});
    updateButtonGroup();

    document.getElementById("delete").addEventListener("click", () => {
        document.getElementById("delete-modal").setAttribute("open", true);
    });

    document.getElementById("close").addEventListener("click", () => {
        document.getElementById("delete-modal").setAttribute("open", false);
    });
    document.getElementById("no").addEventListener("click", () => {
        document.getElementById("delete-modal").setAttribute("open", false);
    });

})();
    </script>
@endsection
