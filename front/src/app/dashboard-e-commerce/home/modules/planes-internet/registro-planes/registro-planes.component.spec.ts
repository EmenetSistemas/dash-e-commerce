import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegistroPlanesComponent } from './registro-planes.component';

describe('RegistroPlanesComponent', () => {
  let component: RegistroPlanesComponent;
  let fixture: ComponentFixture<RegistroPlanesComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [RegistroPlanesComponent]
    });
    fixture = TestBed.createComponent(RegistroPlanesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
