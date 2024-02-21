import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CatalogoExtrasPlanesInternetComponent } from './catalogo-extras-planes-internet.component';

describe('CatalogoExtrasPlanesInternetComponent', () => {
  let component: CatalogoExtrasPlanesInternetComponent;
  let fixture: ComponentFixture<CatalogoExtrasPlanesInternetComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [CatalogoExtrasPlanesInternetComponent]
    });
    fixture = TestBed.createComponent(CatalogoExtrasPlanesInternetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
