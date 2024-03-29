import { Component } from '@angular/core';
import { DataService } from 'src/app/services/data/data.service';

@Component({
	selector: 'app-home',
	templateUrl: './home.component.html',
	styleUrls: ['./home.component.css']
})
export class HomeComponent {
	constructor(
		public dataService: DataService
	) { }
}