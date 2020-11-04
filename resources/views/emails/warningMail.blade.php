@component('mail::message')
<h3> Greetings From blogProject.</h3>

<p> Dear {{ $user->name }} </p>

<p> It is good to have people in our community sharing in on the fun !!
  However, recently, we have found that you have disrupted the community guidelines
  by posting content that falls beyond out rules and regulations. This E-mail is just a friendly
  reminder to keep things clean and follow the communoty srandards, otherwise, we will be forced to
  take rigorous action. Worst case scenario, this may result in a permanent ban from our community and
  website. So Please, refrain from doing such things in the future.
 </p>

 <h2> Thanks, </h2>
 <h5> The {{ config('app.name') }} Management Team </h5>

@endcomponent
