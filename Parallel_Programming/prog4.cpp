/****************************
     Canny edge detection
*****************************/

#include <iostream>
#include <bits/stdc++.h>
#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"
#include "opencv2/opencv.hpp"
#include "opencv2/imgproc/imgproc.hpp"
#include <opencv2/objdetect/objdetect.hpp>
#include <math.h>

using namespace cv; 
using namespace std; 

int main()
{
	// Reading image
	Mat img = imread("1.jpg");

	// Displaying image
	//imshow("Original Image",img);
	//waitKey(0);

	// Converting to grayscale
	Mat img_gray;
	cvtColor(img,img_gray,CV_RGB2GRAY);

	// Displaying grayscale image
	imshow("Original Image",img_gray);
	waitKey(0);

	
	int cols = img_gray.cols;
	int rows = img_gray.rows;

	// Creating sobel operator in x direction
	int sobel_x[3][3] = {-1,0,1,-2,0,2,-1,0,1};
	// Creating sobel operator in y direction
	int sobel_y[3][3] = {1,2,1,0,0,0,-1,-2,-1};


	int radius = 1;
	
	// Handle border issues
    Mat _src;
    copyMakeBorder(img_gray, _src, radius, radius, radius, radius, BORDER_REFLECT101);

    // Create output matrix
    Mat gradient_x = img_gray.clone();
    Mat gradient_y = img_gray.clone();
    Mat gradient_f = img_gray.clone();

    int max=0;

	// Conrrelation loop in x direction 
    
    // Iterate on image 
    for (int r = radius; r < _src.rows - radius; ++r)
    {
        for (int c = radius; c < _src.cols - radius; ++c)
        {
            int s = 0;

            // Iterate on kernel
            for (int i = -radius; i <= radius; ++i)
            {
                for (int j = -radius; j <= radius; ++j)
                {
                    s += _src.at<uchar>(r + i, c + j) * sobel_x[i + radius][j + radius];
                }
            }
            gradient_x.at<uchar>(r - radius, c - radius) = s/8;

            /*if(s>200)
            	gradient.at<uchar>(r - radius, c - radius) = 255;
            else
              	gradient.at<uchar>(r - radius, c - radius) = 0;
            */    
        }
    }

    // Conrrelation loop in y direction 
    
    // Iterate on image 
    for (int r = radius; r < _src.rows - radius; ++r)
    {
        for (int c = radius; c < _src.cols - radius; ++c)
        {
            int s = 0;

            // Iterate on kernel
            for (int i = -radius; i <= radius; ++i)
            {
                for (int j = -radius; j <= radius; ++j)
                {
                    s += _src.at<uchar>(r + i, c + j) * sobel_y[i + radius][j + radius];
                }
            }
            if(s>max)
                max=s;
            gradient_y.at<uchar>(r - radius, c - radius) = s/8;

            /*if(s>200)
                gradient.at<uchar>(r - radius, c - radius) = 255;
            else
                gradient.at<uchar>(r - radius, c - radius) = 0;
            */    
        }
    }

    ///cout<<endl<<"max:"<<max;
    //cout<<img_gray.rows;
    //cout<<endl<<_src.rows;
    cout<<endl<<gradient_x.rows;
    cout<<endl<<gradient_y.rows;
    cout<<endl<<gradient_y.cols;
    cout<<endl<<gradient_f.rows;
    cout<<endl<<gradient_f.cols;
    
    
	//Calculating gradient magnitude
    for(int i=0; i<gradient_f.rows; i++)
    {
        for(int j=0; j<gradient_f.cols; j++)
        {
            gradient_f.at<uchar>(i,j) = sqrt( pow(gradient_x.at<uchar>(i,j),2) + pow(gradient_y.at<uchar>(i,j),2) );  
        
            if(gradient_f.at<uchar>(i,j) >100)
                gradient_f.at<uchar>(i,j) = 0;
            else
                gradient_f.at<uchar>(i,j) = 0;
        }
    }
    
    cout<<endl<<"Max:"<<max;

    imshow("grad x",gradient_x);
	waitKey(0);

    imshow("grad y",gradient_y);
    waitKey(0);

    imshow("grad magnitude",gradient_f);
    waitKey(0);	



	return 0;

}