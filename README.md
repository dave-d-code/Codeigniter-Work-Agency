# Codeigniter-Work-Agency
Codeigniter 3: Automated Work Shifts Booking System

Codeigniter Version 3.0.6: Ongoing personnal Project.
Also uses: Bootstrap, Jquery, Ajax

This project is an automated work shift booking system. The project takes the form of a secure website with 3 main branches.

As an example case study, the site is focused on multiple hospitals being able book doctors & nurses for extra work from an Agency.

This site therefore splits into 3 secure but inter connected branches.
- Client side (hospitals)
- Workers (doctors/nurses)
- Agency (Overview of above)

WHAT DOES THE CODE FOR THIS SITE DO?

The Hospital HR can log onto the site, and request a specific skill set for a doctor/nurse for a future shift.
The framework will automatically tag and limit to the best & relevant workers. This is based on the workers profile:

For Example:

- Working at that hospital
- Matching the required skill set
- Being available (not committed elsewhere)
- Being the best profiled. (ie picks the best rated workers first)

Each worker has their own secure login, and will see a future shift tagged specifically for them. When the first worker accepts the shift, the system will notify the hospital, and untag the other proposed workers.

If the site cannot find any appropiate workers, the shift gets flagged straight to the Agency to deal with.

The Agency has its own login, with overview of the entire process. i.e, which hospitals have booked which workers for any dates.

This project also includes extra features for the Agency branch.

- Ajax updates (every 5 mins) for all tables of shifts.
- JS Script to monitor each table of data. Should any new row of data appear, Jquery is used to highlight the new row of data.

The framework has secure branches, meaning that nobody can view each others information when they are logged in.

Included also is relevant admin/ dashboard sections for all branches, enabling each branch to be mostly independant.
The Agency have overall control of the site, and can remove/ add accounts as necessary.

NOTE: If using this code for ur own project, the core system files have not been included. Please integrate them in your own CI framework.
I have used "FAT MODELS" & "THIN CONTROLLERS" in the framework. The Data_seed_m.php in the models section is a data object (for all view data) to pass around the various models. Much like the $scope in AngularJS.

This project is ongoing, with more features to be added.
