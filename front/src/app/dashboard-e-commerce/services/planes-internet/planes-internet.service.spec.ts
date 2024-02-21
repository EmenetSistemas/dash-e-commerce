import { TestBed } from '@angular/core/testing';

import { PlanesInternetService } from './planes-internet.service';

describe('PlanesInternetService', () => {
  let service: PlanesInternetService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PlanesInternetService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
